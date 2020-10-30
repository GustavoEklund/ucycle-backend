<?php

namespace Domain\UseCase\User;

use Doctrine\ORM\EntityManager;
use Domain\Entity\User;
use Domain\Exception\RequiredValueException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class CreateUserTest
 * @package Domain\UseCase\User
 */
class CreateUserTest extends TestCase
{
    private CreateUser $sut;

    public function setUp(): void
    {
        $entity_manager = $this->createMock(EntityManager::class);
        $this->sut = new CreateUser($entity_manager);
    }

    public function test_assert_given_user_without_full_name_throws_exception(): void
    {
        // Arrange
        $this->expectException(RequiredValueException::class);
        $this->expectExceptionMessage('Nome completo não informado.');
        $this->expectExceptionCode(500);

        $user = $this->createMock(User::class);
        $user->method('getFullName')->willReturn(null);

        // Act, Assert
        $this->sut->execute($user, 'any_password');
    }

    public function test_assert_given_user_without_email_throws_exception(): void
    {
        // Arrange
        $this->expectException(RequiredValueException::class);
        $this->expectExceptionMessage('Email não informado.');
        $this->expectExceptionCode(500);

        $user = $this->createMock(User::class);
        $user->method('getFullName')->willReturn('Any Full Name');
        $user->method('getEmail')->willReturn(null);

        // Act, Assert
        $this->sut->execute($user, 'any_password');
    }

    public function test_assert_given_user_with_invalid_password_throws_exception(): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Senha inválida.');
        $this->expectExceptionCode(500);

        $user = $this->createMock(User::class);
        $user->method('getFullName')->willReturn('Any Full Name');
        $user->method('getEmail')->willReturn('any_email@example.com');
        $user->method('isPasswordValid')->willReturn(false);

        // Act, Assert
        $this->sut->execute($user, 'any_password');
    }

    public function test_assert_given_user_without_created_by_throws_exception(): void
    {
        // Arrange
        $this->expectException(RequiredValueException::class);
        $this->expectExceptionMessage('Criado por não informado.');
        $this->expectExceptionCode(500);

        $user = $this->createMock(User::class);
        $user->method('getFullName')->willReturn('Any Full Name');
        $user->method('getEmail')->willReturn('any_email@example.com');
        $user->method('isPasswordValid')->willReturn(true);
        $user->method('getCreatedBy')->willReturn(null);

        // Act, Assert
        $this->sut->execute($user, 'any_password');
    }
}