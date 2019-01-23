<?php

namespace Modelizer\Enfield\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\MinkContext;
use Laravel\Dusk\Concerns\WaitsForElements;
use Laravel\Dusk\ElementResolver;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    use WaitsForElements;

    /**
     * The default wait time in seconds.
     *
     * @var int
     */
    public static $waitSeconds = 5;

    /**
     * @var ElementResolver
     */
    protected $resolver;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /** @BeforeScenario */
    public function before(BeforeScenarioScope $scope)
    {
        if (method_exists($this->getSession()->getDriver(), 'getWebDriver')) {
            $this->resolver = new ElementResolver($this->getSession()->getDriver()->getWebDriver());

            return;
        }
    }

    /**
     * Checks, that current page PATH is equal to specified
     * Example: Then I should be on "login" route
     *
     * @Then /^(?:|I )should be on "(?P<route>[^"]+)" route$/
     */
    public function assertRoute($route)
    {
        $this->assertSession()->addressEquals(route($route));
    }

    /**
     * @Given /^(?:|I )am authorized member/
     */
    public function iAmAuthorizedMember()
    {
        $this->visitPath('/_dusk/login/' . $this->getMinkParameter('member_login'));
        $this->visit(partnerRoute('dashboard'));
    }

    /**
     * @Given /^(?:|I )scroll down(?:| (?P<pixel>[0-9]+)px)$/
     */
    public function iScrollDown($pixel = null)
    {
        $pixel = $pixel ?? "document.body.scrollHeight";

        $this->getSession()->getDriver()->executeScript("window.scrollTo(0,{$pixel});");
    }

    /**
     * @javascript
     * @Given /^(?:|I )click on "(?P<selector>[^"]+)"$/
     */
    public function iClickTheElement($selector)
    {
        $this->resolver->findOrFail($selector)->click();
    }

    /**
     * Checks, that page contains specified text
     * Example: Then I should see "Who is the Batman?"
     * Example: And I should see "Who is the Batman?"
     *
     * @Then /^(?:|I )should see "(?P<text>(?:[^"]|\\")*)" in popup$/
     */
    public function assertPageContainsTextInPopup($text)
    {
        $this->waitForText($text);

        $this->assertSession()->pageTextContains($this->fixStepArgument($text));
    }

    /**
     * @Given /^pause for(?P<milliseconds>(?:| [0-9]+)) seconds$/
     */
    public function pause($milliseconds = 1000)
    {
        usleep($milliseconds * 1000);

        return $this;
    }
}
