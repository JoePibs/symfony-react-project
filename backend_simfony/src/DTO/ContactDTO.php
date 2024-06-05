<?php 
namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public string $firstname = '';

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public string $lastname = '';

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public string $subject = '';

    public string $content = '';
    
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';

    public string $submitted_at = '';
}