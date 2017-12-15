<?php
namespace WF3\Controller;

use Silex\Application;
//cette ligne nous permet d'utiliser le service fourni par symfony pour gérer 
// les $_GET et $_POST
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\ReservationType;
use WF3\Domain\Livredor;
use WF3\Form\Type\LivredorType;
use WF3\Form\Type\ContactType;
use WF3\Form\Type\UploadImageType;
use WF3\Domain\PaypalInvoice;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//Paypal ou je pige que dalle mais ça va le faire !
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

use WF3\Domain\Sale;


class HomeController{

	// Page d'accueil qui affiche tous les spectacles :
	 public function homePageAction(Application $app){
        
        $spectacles = $app['dao.spectacle']->LastNineArticles();
        $archives= $app['dao.spectacle']->ArchiveShow();
        $presses = $app['dao.press']->findAll();
        return $app['twig']->render('index.html.twig', array('spectacles'=>$spectacles,
              'archives'=>$archives,
              'presses'=>$presses));
    }


	// Page de paiement qui affiche le boutton paypal :
	public function paymentsAction(Application $app){
	 	return $app['twig']->render('payments.html.twig');
	}


	//page contact
	public function contactAction(Application $app, Request $request){
        
        $contactForm = $app['form.factory']->create(ContactType::class);
        $contactForm->handleRequest($request);
        
        if ($contactForm->isSubmitted() && $contactForm->isValid()){
            $data = $contactForm->getData();
            $message = \Swift_Message::newInstance()
                        ->setSubject($data['subject'])
                        ->setFrom(array('promo5wf3@gmx.fr'))
                        ->setTo(array('batty.arnaud@hotmail.fr'))
                        ->setBody($app['twig']->render('contact.email.html.twig',
                            array('name'=>$data['name'],
                                   'email' => $data['email'],
                                   'message' => $data['message']
                            )
                        ), 'text/html');

            $app['mailer']->send($message);
            $app['session']->getFlashBag()->add('success', 'Email envoyé, nous vous répondrons dès que possible !');
            return $app->redirect($app['url_generator']->generate('home'));

        }
        return $app['twig']->render('contact.html.twig', array(
            'title' => 'Contact Us',
            'contactForm' => $contactForm->createView(),
            'data' => $contactForm->getData()
        ));
	}

















    // Page de reservation #C'est la merde !
	public function reservationAction(Application $app, Request $request){
        //Récupération des données dans $data
        $data = $app['dao.spectacle']->findAll();
        $listed= array('Choisissez votre spectacle'=>0);
        
        foreach($data as $spectacle){
            $listed[$spectacle->getTitle()] = $spectacle->getId();
        }

        $reservationForm = $app['form.factory']->create(ReservationType::class);

        $reservationForm->add('spectacles', ChoiceType::class, array('choices'=>$listed));
        
        $reservationForm->handleRequest($request);
        
        if ($reservationForm->isSubmitted() ){

            $dataForm = $reservationForm->getData();
            $id = $dataForm['spectacles'];
            
            //Récupération du spectacle :
            $spectacle = $app['dao.spectacle']->find($id);
            //Récupération du prix :
            $prix = $spectacle->getPrice();
            //Récupération du nombre de places :
            $places = $dataForm['ticket'];
            //Calcul du prix en fonction du nombre de places :
            $prixTotal = $places*$prix;
            //Récupération de l'Email :
            $email = $dataForm['email'];
            //
            //Utilisation Paypal
            $expressCheckout = $app['paypal']->createExpressCheckout();

                //get unique invoice number
                $invoice = new PaypalInvoice();
                $ppinvoice = $app['dao.paypalInvoice']->insert($invoice);
                $numeroUnique = $app['db']->lastInsertId();
                $expressCheckout
                ->addItem("$places place(s) pour " . $spectacle->getTitle(), 1, 'sku0', $prixTotal)
                ->setDescription($spectacle->getId() . "--Achetez les places de spectacle")
                ->setInvoiceNumber($numeroUnique)
                ->setSuccessUrl('http://localhost' . $app['url_generator']->generate('success'))
                ->setFailureUrl('http://localhost' . $app['url_generator']->generate('error'));

                

            $approvalUrl = $expressCheckout->getApprovalUrl($app['paypal']->getPayPalApiContext());

            return $app->redirect($approvalUrl);
        }
        return $app['twig']->render('reservation.html.twig', array(
            'reservationForm' => $reservationForm->createView(),
            'data' => $reservationForm->getData()
        ));
	}





    //Liens vers les pages Error et Success :
	public function successAction(Application $app, Request $request){
            //debug
            $spectacleId = 0;
            //
            $user = $app['user'];

            $paymentId = $request->query->get('paymentId');
            $payerId = $request->query->get('PayerID');
            $executionSuccessful = $app['paypal']->executePayment($payerId, $paymentId);
            $payment = Payment::get($paymentId, $app['paypal']->getPayPalApiContext());

            $payer = $payment->getPayer()->getPayerInfo();
            $email = $payer->getEmail();
            $firstName = $payer->getFirstName();
            $lastName = $payer->getLastName();
            $shipping = $payer->getShippingAddress();
            $city = $shipping->getCity();
            $state = $shipping->getState();
            $postalCode = $shipping->getPostalCode();
            $phone = $payer->getPhone();
            $country = $shipping->getCountryCode();        
            $adress = $firstName . ' ' . $lastName . ' - ' .
            $shipping->getLine1() . ' - ' . $shipping->getLine2() . ' - ' .
            $city . ' - ' . $postalCode . ' - ' . $state . ' - ' . $country;
            $createtime = $payment->getCreateTime();

            foreach($payment->getTransactions() as $transaction)
            
            {
                $shipping = $transaction->getItemList()->getShippingAddress();
                $amount = $transaction->getAmount()->getTotal();
                $currency = $transaction->getAmount()->getCurrency();
                $tab = explode('--', $transaction->getDescription());
                $spectacleId = $tab[0];
                $itemDescription = $tab[1];
                //on récupère l'abonnement avec l'id renvoyée par paypal
                $spectacle = $app['dao.spectacle']->find($spectacleId);
                if($spectacle->getPrice() == $amount AND $currency == 'EUR' AND $executionSuccessful){
                    //prices matches, product ids matches,currency is US Dollar and payment is valid
                    $status = 'valid';
                    $message = "Félicitaion, vous avez bien réservé une place ! Vous ne le regretterez pas ! ";
                    //send notifications
                    //first purchase confirmation to customer
                    $notification = \Swift_Message::newInstance()
                    ->setSubject("La Cité's Compagnie - Réservation")
                    ->setFrom(array('promo5wf3@gmx.fr'))
                    ->setTo($email)
                    /*->setBody($app['twig']->render('email/purchase.confirmation.email.html.twig',   // email template
                        array('name'      => $user->getUsername(),
                              'product'     => $product,
                        )),'text/html');*/
                    ->setBody($message);
                    $app['mailer']->send($notification);
                    
                    //then sale notification to exaequo
                    $notification3 = \Swift_Message::newInstance()
                    ->setSubject('Vente d\'un abonnement')
                    ->setFrom(array('promo5wf3@gmx.fr'))
                    ->setTo(array('batty.arnaud@hotmail.fr'))
                    /*->setBody($app['twig']->render('email/sale.confirmation.email.html.twig',   // email template
                        array('name'      => $seller->getUsername(),
                              'product'     => $product,
                              'adress' => $adress,
                              'buyer' => $user->getUsername()
                        )),'text/html');*/
                    ->setBody($message);
    
                    $app['mailer']->send($notification3);
                }
                else{
                    $status = 'invalid';
                    $message = 'Votre paiement n\'as pas été accepté.';
                
                    $notification4 = \Swift_Message::newInstance()
                    ->setSubject('error : Sale notification on peddle-art.com')
                    ->setFrom(array('promo5wf3@gmx.fr'))
                    ->setTo(array('contact@le-web-developpeur.com'))
                    /*->setBody($app['twig']->render('email/sale.confirmation.email.html.twig',   // email template
                        array('name'      => $seller->getUsername(),
                              'product'     => $product,
                              'adress' => $adress,
                              'buyer' => $user->getUsername(). ' - ' . $productID . ' / ' . $productId . ' -- ' . $dbAmount . ' / ' . $amount . ' -- ' . $currency . ' -- ' . $executionSuccessful
                        )),'text/html');*/
                        ->setBody($message);
    
                    $app['mailer']->send($notification4);
                }
                    
                
            }
            //ici c'est l'enregistrement de la transaction dans la base: il faut créer une classe saleDAO (tu l'appelles comme tu veux) et la table associée qui va stocker les informations 
            $sale = new Sale();
            $sale->setAmount($amount);
            $sale->setBuyerid((int)$user->getId());
            $sale->setPaymentid($paymentId);
            $sale->setPayerid($payerId);
            $sale->setSpectacleid($spectacleId);
            $sale->setEmail($email);
            $sale->setCreatetime($createtime);
            $sale->setPhone($phone);
            $sale->setShipping($adress);
            $sale->setStatus($status);
            $app['dao.sale']->insert($sale);

        return $app['twig']->render('reservationSuccess.html.twig', array('test'=>$request->query, 'idspectacle'=>$spectacleId));
   }

	public function errorAction(Application $app){
        return $app['twig']->render('reservationError.html.twig');
   }



















	
	//page du livre d'or :
	public function livreDorAction(Application $app, Request $request){
		$livredor = new Livredor();
		$livredorForm = $app['form.factory']->create(LivredorType::class, $livredor);
		$livredorForm->handleRequest($request);
		if($livredorForm->isSubmitted() && $livredorForm->isValid()){
			$app['dao.livredor']->insert($livredor);
            $app['session']->getFlashBag()->add('success', 'Message enregistré, merci pour votre contribution !');
            return $app->redirect($app['url_generator']->generate('livreDorMessages'));
		}

		return $app['twig']->render('livredor.html.twig',
			array('livredorForm'=>$livredorForm->createView())
		);
	}
    
    //page du livre d'or :
    public function livreDorMessagesAction(Application $app){
        
        $messages = $app['dao.livredor']->findLast10();

        return $app['twig']->render('livredor.message.html.twig',
            array('messages'=>$messages)
        );
    }
    
    //Utilisation de Paypal :
    public function achatPaypalAction(Application $app, Request $request){
        
    }


}