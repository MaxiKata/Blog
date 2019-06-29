<?php

namespace Blog\App\Controller;

/**
 * Class ContactController
 * @package Blog\App\Controller
 */
class ContactController
{
    /**
     * Redirect to the Home Page
     */
    public function indexAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

        $url = "index.php"; ?>
        <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
    <?php }

    /**
     * Send a form to the admin if email is valid
     */
    public function emailAction()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        if(isset($email)) {

            // EDIT THE 2 LINES BELOW AS REQUIRED
            $email_to = "guilhemmaxime@gmail.com";
            $email_subject = "Nouvelle demande de contact";

            $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING); // required
            $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING); // required
            $email_from = filter_input(INPUT_POST, 'email_from', FILTER_SANITIZE_STRING); // required
            $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING); // not required
            $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING); // required

            // validation expected data exists
            if(empty($first_name) ||
                empty($last_name) ||
                empty($email_from) ||
                empty($comments)) {
                    $url = "index.php?error=emptyFields"; ?>
                    <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }

            $email_message = "Form details below.\n\n";

            $email_message .= "First Name: ".$this->clean_string($first_name)."\n";
            $email_message .= "Last Name: ".$this->clean_string($last_name)."\n";
            $email_message .= "Email: ".$this->clean_string($email_from)."\n";
            $email_message .= "Telephone: ".$this->clean_string($telephone)."\n";
            $email_message .= "Comments: ".$this->clean_string($comments)."\n";

        // create email headers
            $headers = 'From: ' . $email_from . "\r\n" .
                'Reply-To: ' . $email_from."\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($email_to, $email_subject, $email_message, $headers);

            $url = "index.php?success=contact"; ?>

            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>

            <?php

        }
    }

    /**
     * @param $string
     * @return mixed
     * Filter unauthorized word
     */
    private function clean_string($string) {
        $bad = array("content-type","bcc:","to:","cc:","href");
        return str_replace($bad,"",$string);
    }
}