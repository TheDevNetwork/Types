<?php

use Tdn\PhpTypes\Type\BooleanType;

/**
 * Class RoboFile.
 *
 * This file has *nix level commands and will not run on doze hosts.
 */
class RoboFile extends \Robo\Tasks
{
    /**
     * Watch composer
     */
    public function watchComposer()
    {
        $this->taskWatch()->monitor('composer.json', function () {
            $this->taskComposerUpdate('/usr/local/bin/composer')->run();
        })->run();
    }

    /**
     * Builds project. (Runs fix:cs, test:run, build:documentation)
     */
    public function build()
    {
        $this->fixCs();
        $this->testRun();
        $this->buildDocumentation();
    }

    /**
     * Runs PHP-CS-Fixer.
     */
    public function fixCs()
    {
        $task = 'vendor/bin/php-cs-fixer fix ./%s --rules=@Symfony,-space_after_semicolon';
        $dirs = [
            'Tests',
            'Math',
            'Exception',
            'Type',
        ];

        foreach ($dirs as $dir) {
            $this->taskExec(sprintf($task, $dir))->run();
        }
    }

    /**
     * Runs PHPUnit, PHPCS checker, Parallel Lint.
     *
     * @param bool $withHtml
     */
    public function testRun($withHtml = false)
    {
        $phpUnit = $this->taskPhpUnit()->configFile('phpunit.xml.dist');
        $this->_remove('build/logs');
        $this->_remove('build/html');

        $this->taskExec('./vendor/bin/parallel-lint --exclude vendor --exclude build .')->run()->stopOnFail();
        $this->taskExec('./vendor/bin/phpcs --standard=.phpcs.xml')->run()->stopOnFail();
        if (BooleanType::valueOf($withHtml)->isTrue()) {
            $phpUnit->option('--coverage-html', 'build/html');
        }

        $phpUnit->run()->stopOnFail();
    }

    /**
     * Runs build:documentation-api, build:documentation-mkdocs
     */
    public function buildDocumentation()
    {
        $this->buildDocumentationApi();
        $this->buildDocumentationMkdocs();
    }

    /**
     * Builds API Documentation using Sami.
     */
    public function buildDocumentationApi()
    {
        $this->_remove('build/api');
        $this->_remove('build/cache');
        $this->taskExec('vendor/bin/sami.php update sami.php')->run();
    }

    /**
     * Builds Project documentation using MKDocs.
     */
    public function buildDocumentationMkdocs()
    {
        $this->_remove('build/docs');
        $this->taskExec('python -W ignore $(which mkdocs) build --clean')->run();
    }

    /**
     * Publishes documentation to github pages.
     *
     * @param bool $buildDocs
     * @param string $message
     */
    public function publishDocumentation($buildDocs = false, $message = 'Update from RoboFile.')
    {
        $this->say('If using a VM make sure you are forwarding the ssh agent for git!');

        $setUpDir = function($target, $branch) {
            return "git clone $(git remote get-url origin) {$target} && cd {$target} && git checkout {$branch}";
        };

        $doPublish = function($target, $branch) use ($message) {
            return "cd {$target} && if [ $(git diff --name-only | wc -l) -gt 0 ]; " .
            "then git add -u . && git add . && git commit -m '{$message}' && git push origin {$branch}; fi";
        };

        //It's actually a string
        if (BooleanType::valueOf($buildDocs)->isTrue()) {
            $this->buildDocs();
        }

        $toDir = 'publish';
        $this->taskExec($setUpDir($toDir, 'gh-pages'))->run(); //clone + checkout proper branch
        $this->taskExec(sprintf('rm -rf %s/*', $toDir))->run(); //clean
        $this->_mkdir($toDir . '/api'); //Remake API dir
        $this->taskParallelExec()
            ->process(sprintf('cp -r build/doc/* %s', $toDir)) //Copy build doc files
            ->process(sprintf('cp -r build/api/* %s', $toDir . '/api/')) //Copy build API files
            ->run()
        ;
        $this->taskExec($doPublish($toDir, 'gh-pages'))->run()->stopOnFail();
        $this->_remove($toDir);
    }
}
