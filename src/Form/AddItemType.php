<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AddItemType extends AbstractType {
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator) {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->setAction($this->urlGenerator->generate('cart_add_item', array(
            'id' => $builder->getData()
        )));

        $builder->add('id',HiddenType::class);
        $builder->add('submit', SubmitType::class, array(
            'label' => 'addItem.button',
            'attr' => array(
                'icon' => 'fa fa-cart-plus'
            )
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}
