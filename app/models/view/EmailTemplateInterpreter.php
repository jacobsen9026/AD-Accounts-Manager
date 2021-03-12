<?php


namespace app\models\view;


use App\Models\Domain\DomainUser;
use App\Models\Model;
use System\App\AppLogger;

class EmailTemplateInterpreter extends Model
{
    const USERNAME_PLACEHOLDER = "{{USERNAME}}";
    const EMAIL_ADDRESS_PLACEHOLDER = "{{EMAIL_ADDRESS}}";
    const FULL_NAME_PLACEHOLDER = "{{FULL_NAME}}";

    /**
     * @param string $templateBody
     * @param array|null $domainUser
     * @return string|string[]
     */
    public static function interpret(string $templateBody, array $domainUser = null)
    {
        if ($domainUser == null) {
            $domainUser = new DomainUser();
            $domainUser->setUsername('ExampleUsername')
                ->setEmailAddress('example@example.com')
                ->activeDirectory->setDisplayName("Exmaple User");
        }
        AppLogger::get()->info("Substituting template with user:");
        AppLogger::get()->info($domainUser->getUsername());

        $templateBody = self::substituteUsername($templateBody, $domainUser->getUsername());
        $templateBody = self::substituteDisplayName($templateBody, $domainUser->getDisplayName());
        $templateBody = self::substituteEmailAddress($templateBody, $domainUser->getEmail());
        return $templateBody;
    }

    private static function substituteUsername(string $templateBody, string $username)
    {
        AppLogger::get()->info("Replacing usernames in email template");
        return str_replace(self::USERNAME_PLACEHOLDER, $username, $templateBody);
    }

    private static function substituteDisplayName(string $templateBody, string $fullName)
    {
        AppLogger::get()->info("Replacing full names in email template");
        return str_replace(self::FULL_NAME_PLACEHOLDER, $fullName, $templateBody);
    }

    private static function substituteEmailAddress(string $templateBody, string $emailAddress)
    {
        AppLogger::get()->info("Replacing email addresses in email template");

        return str_replace(self::EMAIL_ADDRESS_PLACEHOLDER, $emailAddress, $templateBody);
    }
}