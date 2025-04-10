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
use Symfony\Component\Validator\Constraints as Assert;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
         * @var Showtime
         */
        $showtime = $options['showtime'];
        $showtimeSeats = $showtime->getShowtimeSeats();

        $builder->add('showtimeSeats', ChoiceType::class, [
            'expanded' => true,
            'multiple' => true,
            'choices' => $showtimeSeats,
            'choice_value' => fn(ShowtimeSeat $seat) => $this->choiceValue($seat),
            'choice_label' => fn(ShowtimeSeat $seat) => $this->choiceLabel($seat),
            'choice_attr' => fn(ShowtimeSeat $seat) => $this->choiceAttr($seat),
            'constraints' => [
                new Assert\NotBlank(),
            ],
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

    private function choiceValue(ShowtimeSeat $showtimeSeat) {
        return $showtimeSeat->getId();
    }

    private function choiceLabel(ShowtimeSeat $showtimeSeat) {
        $seat = $showtimeSeat->getSeat();

        return "{$seat->getRow()}:{$seat->getCol()}";
    }

    private function choiceAttr(ShowtimeSeat $showtimeSeat) {
        if ($showtimeSeat->getReservedSeat()) {
            return ['disabled' => 'disabled'];
        }

        return [];
    }
}
