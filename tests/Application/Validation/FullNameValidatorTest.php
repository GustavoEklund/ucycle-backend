<?php

namespace Tests\Application\Validation;

use Application\Validation\FullNameValidator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RangeException;

/**
 * Class FullNameValidatorTest
 * @package Tests\Application\Validation
 */
class FullNameValidatorTest extends TestCase
{
    protected FullNameValidator $sut;

    public function setUp(): void
    {
        $this->sut = new FullNameValidator();
    }

    public function nameWithLessThanTwoCharacters(): array
    {
        return [
            ['A Full Name'],
            ['Any F Name'],
            ['ANY FULL N'],
        ];
    }

    public function test_assert_full_name_with_less_than_2_names(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O nome completo deve ter pelo menos 2 nomes.');
        $this->expectExceptionCode(400);

        $this->sut->validate('FullName');
    }

    /**
     * @dataProvider nameWithLessThanTwoCharacters
     * @param string $full_name
     */
    public function test_assert_name_with_less_than_2_chars_throw_exception(string $full_name): void
    {
        // Arrange
        $this->expectException(RangeException::class);
        $this->expectExceptionMessage('O nome ou sobrenome deve ter pelo menos 2 caracteres.');
        $this->expectExceptionCode(400);

        // Act, Assert
        $this->sut->validate($full_name);
    }

    public function test_assert_parse_full_name_makes_all_words_lowercase_and_first_letter_capital(): void
    {
        self::assertEquals(
            'Any Full Name',
            $this->sut->parseFullName('ANY FULL NAME')
        );
    }

    public function test_assert_parse_full_name_removes_special_characters(): void
    {
        self::assertNotEquals(
            'ANY <FULL/> NAME #',
            $this->sut->parseFullName('ANY <FULL/> NAME #',)
        );
    }

    public function test_assert_validate_parses_the_full_name_before_returning(): void
    {
        self::assertEquals(
            'Any Full Name',
            $this->sut->validate('aNY fULL nAME')
        );
    }
}