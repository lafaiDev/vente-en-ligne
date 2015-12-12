<?php
namespace Catalogue\UtilisateurBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{ /** * {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    { $builder ->add('username', null, array('label' => "Nom d'utilisateur"))
    ->add('email', null, array('required' => false, 'label' => 'E-mail'))
    ->add('plainPassword', 'repeated', array(
        'type' => 'password',
        'invalid_message' => 'Les mots de passe doivent être identiques.',
        'required' => $options['passwordRequired'],
        'first_options'  => array('label' => 'Mot de passe'),
        'second_options' => array('label' => 'Répétez le mot de passe'),
    ));
    if ($options['lockedRequired']) {
        $builder->add('locked', null, array('required' => false,
            'label' => 'Vérouiller le compte'));
    }

}

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Catalogue\UtilisateurBundle\Entity\User',
            'passwordRequired' => true,
            'lockedRequired' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'catalogue_utilisateurbundle_user';
    }
}