<?php
/**
 * This file is a part of CSCFA datagrid project.
 * 
 * The datagrid project is a symfony bundle written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Form
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\DataGridBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * PaginatorLimit class.
 *
 * The PaginatorLimit implement
 * form to select the paginator 
 * limit.
 *
 * @category Form
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class PaginatorLimit extends AbstractType
{
    /**
     * BuildForm
     * 
     * This build the common
     * type form
     * 
     * @param FormBuilderInterface $builder - the form builder
     * @param array                $options - the form options
     * 
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("page", "hidden", array())
            ->add("lastLimit", "hidden", array())
            ->add("limit", "choice", array("choices"=>$options["data"]->getAllowedLimits(), "attr"=>array("selected"=>array_search($options["data"]->getLastLimit(), $options["data"]->getAllowedLimits()))))
            ->add("submit", "submit", array());
    }

    /**
     * configureOptions
     * 
     * Configure the type options
     * 
     * @param OptionsResolver $resolver - the option resolver
     * 
     * @see \Symfony\Component\Form\AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Cscfa\Bundle\DataGridBundle\Objects\PaginatorLimitForm'));
    }
    
    /**
     * Get name
     * 
     * Return the type name
     * 
     * @see    \Symfony\Component\Form\FormTypeInterface::getName()
     * @return string - the type name
     */
    public function getName()
    {
        return "paginatorLimit";
    }
}