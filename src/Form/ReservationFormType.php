<?php

namespace App\Form;

use App\Entity\ShowtimeSeat;
use App\Entity\User;
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
        $user = $options['user'];
        $showtimeSeats = $showtime->getShowtimeSeats();

        $builder->add('seats', ChoiceType::class, [
            'expanded' => true,
            'multiple' => true,
            'choices' => $showtimeSeats,
            'choice_value' => fn(ShowtimeSeat $seat) => $this->choiceValue($seat),
            'choice_label' => fn(ShowtimeSeat $seat) => $this->choiceLabel($seat),
            'choice_attr' => fn(ShowtimeSeat $seat) => $this->choiceAttr($seat, $user),
        ]);

        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'showtime' => null,
            'user' => null,
        ]);

        $resolver->setAllowedTypes('showtime', Showtime::class);
        $resolver->setAllowedTypes('user', User::class);
    }

    private function choiceValue(ShowtimeSeat $seat)
    {
        return $seat->getId();
    }

    private function choiceLabel(ShowtimeSeat $seat)
    {
        $seat = $seat->getSeat();

        return "{$seat->getRow()}:{$seat->getCol()}";
    }

    private function choiceAttr(ShowtimeSeat $seat, User $user): array
    {
        $reservedSeat = $seat->getReservedSeat();

        if ($reservedSeat) {
            $customer = $reservedSeat->getReservation()->getCustomer();

            return $customer === $user ?
                ['checked' => 'checked'] :
                ['disabled' => 'disabled'];
        }

        return [];
    }
}
