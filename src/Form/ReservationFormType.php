<?php

namespace App\Form;

use App\Entity\Seat;
use App\Form\Type\CinemaLayoutType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Showtime;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
         * @var Showtime
         */
        $showtime = $options['showtime'];
        $seats = $showtime->getHall()->getSeats();
        $reservations = $showtime->getReservations();
        $reservationsSeats = $reservations->map(
            function ($reservation) {
                return $reservation->getReservedSeats();
            }
        );

        $builder->add('seats', ChoiceType::class, [
            'expanded' => true,
            'multiple' => true,
            'choices' => $seats,
            'choice_value' => fn(Seat $seat) => $seat->getId(),
            'choice_label' => fn(Seat $seat) => "{$seat->getRow()}:{$seat->getCol()}",
            'group_by' => fn(Seat $seat) => $seat->getRow(),
        ]);

        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'showtime' => null,
        ]);

        $resolver->setAllowedTypes('showtime', Showtime::class);
    }
}
