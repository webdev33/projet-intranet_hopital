<?php

// src/Application/Sonata/UserBundle/EventListener/editProfileListener.php

namespace Application\Sonata\UserBundle\EventListener;

use Application\Sonata\UserBundle\Entity\User;

class editProfileListener
{
    private $mailer;

    public function preUpdate(\Doctrine\ORM\Event\PreUpdateEventArgs $eventArgs)
    {
        if ($eventArgs->getEntity() instanceof User) {
            /**
             * SET DATE_SORTIE ON NULL IF USER IS REACTIVATE AFTER DESACTIVATION (because date_sortie < now)
             */
            if ($eventArgs->hasChangedField('enabled') && $eventArgs->getNewValue('enabled') == true && $eventArgs->getEntity()->getDateSortie() < new \DateTime('now')) {
                $eventArgs->getEntity()->setDateSortie(null);
                $eventArgs->getEntity()->setRaisonSortie(null);
            }

            /**
             * Change in profile => pointeur field to 1 (back to 0 by the admin in the BDD directly)
             */
            if ( $eventArgs->hasChangedField('email') ||
                 $eventArgs->hasChangedField('phone') ||
                 $eventArgs->hasChangedField('adresse') ||
                 $eventArgs->hasChangedField('ville') ||
                 $eventArgs->hasChangedField('zip') ||
                 $eventArgs->hasChangedField('photo_id') ||
                 $eventArgs->hasChangedField('service_id') ||
                 $eventArgs->hasChangedField('enabled') ||
                 $eventArgs->hasChangedField('password') ||
                 $eventArgs->hasChangedField('date_of_birth') ||
                 $eventArgs->hasChangedField('firstname') ||
                 $eventArgs->hasChangedField('lastname') ||
                 $eventArgs->hasChangedField('date_entree') ||
                 $eventArgs->hasChangedField('date_sortie') ||
                 $eventArgs->hasChangedField('raison_sortie') ||
                 $eventArgs->hasChangedField('username') )
            {
                 $eventArgs->getEntity()->setPointeur(true);
            }


            /**
             * SEND MAIL ON PROFILE CHANGES
             */
            if ( $eventArgs->hasChangedField('email') ||
                 $eventArgs->hasChangedField('phone') ||
                 $eventArgs->hasChangedField('adresse') ||
                 $eventArgs->hasChangedField('ville') ||
                 $eventArgs->hasChangedField('zip')  )
            {
                $em = $eventArgs->getObjectManager();
                $rhs = $em->getRepository('Application\Sonata\UserBundle\Entity\User')->findByRole('ROLE_RH');

                $mailsArray = [];
                foreach ($rhs as $rh) {
                    $mailsArray[] = $rh->getEmail();
                }

                /**
                 * TODO setFrom
                 */
                $message = \Swift_Message::newInstance()
                    ->setSubject('Changements dans le profil de ' . $eventArgs->getEntity())
                    ->setFrom('send@example.com')
                    ->setTo(array('wcs.hopital@gmail.com'))
                    ->setCc($mailsArray)
                    ->setBody(
                        $this->getMailBody($eventArgs->getEntityChangeSet(), $eventArgs->getEntity()),
                        'text/html'
                    );
                $this->mailer->send($message);
            }
        }
    }

    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    private function getMailBody($changes, $user)
    {
        $result = '<h1>Changements dans le profile de '. $user .'</h1>';

        foreach ($changes as $property=>$change) {
            if ( $property === "email" ||
                 $property === "phone" ||
                 $property === "ville" ||
                 $property === "adresse" ||
                 $property === "zip" )
            {
                $result .= "<strong>".$property." :</strong> ".$change[0]." -> ".$change[1]. '<br/><br/>';
            }
        }
        return $result;
    }

}
