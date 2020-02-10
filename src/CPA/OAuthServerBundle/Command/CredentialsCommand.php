<?php

namespace CPA\OAuthServerBundle\Command;

//use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//use Symfony\Component\Console\Input\InputArgument;
//use Symfony\Component\Console\Input\InputOption;
//use Symfony\Component\Console\Input\InputInterface;
//use Symfony\Component\Console\Output\OutputInterface;
use OAuth2;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CredentialsCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('oauth2:credentials')
                ->setDescription('Executes OAuth2 Credentials grant');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $credentialsClient = $this->getContainer()->get('stark_industries_client.credentials_client');
        $accessToken = $credentialsClient->getAccessToken();
//        var_dump($accessToken);
        die('$accessToken');
        $output->writeln(sprintf('Obtained Access Token: <info>%s</info>', $accessToken));

        $url = 'http://knpsf3-test.dev/app_dev.php/api/articles';
//        $url = 'http://knpsf3-test.dev/app_dev.php/authorize';
//        $url = 'http://www.example.com';
        $output->writeln(sprintf('Requesting: <info>%s</info>', $url));
        $response = $credentialsClient->fetch($url);
//        var_dump($response);
        die('$response');
        $output->writeln(sprintf('Response: <info>%s</info>', var_export($response, true)));
    }

}
