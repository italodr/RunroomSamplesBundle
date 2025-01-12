<?php

declare(strict_types=1);

/*
 * This file is part of the RunroomSamplesBundle.
 *
 * (c) Runroom <runroom@runroom.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Runroom\SamplesBundle\Tests\Forms\Unit;

use Runroom\SamplesBundle\Forms\Form\Type\ContactHubspotFormType;
use Runroom\SamplesBundle\Forms\Model\ContactHubspot;
use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Constraints\Cascade;
use Symfony\Component\Validator\Validation;

class ContactHubspotFormTypeTest extends TypeTestCase
{
    /** @test */
    public function submitValidData(): void
    {
        $model = new ContactHubspot();
        $form = $this->factory->create(ContactHubspotFormType::class, $model);
        $form->submit([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '123456789',
            'comment' => 'Lorem ipsum',
            'privacyPolicy' => true,
        ]);
        self::assertTrue($form->isSynchronized());
        self::assertInstanceOf(ContactHubspot::class, $form->getData());
        self::assertSame('John Doe', $model->getName());
        self::assertSame('johndoe@example.com', $model->getEmail());
        self::assertSame('123456789', $model->getPhone());
        self::assertSame('Lorem ipsum', $model->getComment());
        self::assertTrue($model->getPrivacyPolicy());
    }

    /**
     * @todo: Simplify this when dropping support for Symfony 4.4
     *
     * @psalm-suppress TooManyArguments
     *
     * @return AbstractExtension[]
     *
     * @see: enableAnnotationMapping accepts one argument and it must be set to true on Symfony 5.2
     */
    protected function getExtensions(): array
    {
        $annotationMappingArgument = class_exists(Cascade::class) ? true : null;

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping($annotationMappingArgument)
            ->getValidator();

        return [new ValidatorExtension($validator)];
    }
}
