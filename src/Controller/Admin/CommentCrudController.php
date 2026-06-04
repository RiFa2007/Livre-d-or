<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('conference');
        yield TextField::new('author');
        yield EmailField::new('email');
        yield TextareaField::new('text');
        yield ImageField::new('photoFilename')
            ->setBasePath('/uploads/photos')
            ->setLabel('Photo')
             ->onlyOnIndex()
        ;
        yield TextField::new('photoFilename')
            ->onlyOnIndex()
        ;
        yield TextField::new('state')->onlyOnIndex();

        $createdAt = DateTimeField::new('createdAt')->setFormTypeOptions([
            'years' => range(date('Y'), date('Y') + 5),
            'widget' => 'single_text',
        ]);
        if (Crud::PAGE_EDIT === $pageName) {
            yield $createdAt->setFormTypeOption('disabled', true);
        }
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        // configuration de l'affichage du dashbord des commentaires
        return $crud
            ->setEntityLabelInSingular('Conference Comment')
            ->setEntityLabelInPlural('Conference Comments')
            ->setSearchFields(['author', 'text', 'email'])
            ->setDefaultSort(['createdAt' => 'DESC'])
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('conference'));
    }
}
