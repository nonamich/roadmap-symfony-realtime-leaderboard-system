<?php

namespace App\Form;

use App\Entity\Seat;
use App\Entity\ShowtimeSeat;
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
        $showtimeSeats = $showtime->getShowtimeSeats();
        // $reservations = $showtime->getReservations();
        // $reservationsSeats = $reservations->map(
        //     function ($reservation) {
        //         return $reservation->getReservedSeats();
        //     }
        // );

        $builder->add('showtimeSeats', ChoiceType::class, [
            'expanded' => true,
            'multiple' => true,
            'choices' => $showtimeSeats,
            'choice_value' => fn(ShowtimeSeat $showtimeSeat) => $showtimeSeat->getId(),
            'choice_label' => fn(ShowtimeSeat $showtimeSeat) => "{$showtimeSeat->getId()}",
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
