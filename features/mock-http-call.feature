Feature: Mock HTTP call
    In order to test how my system behaves with third party services
    As a developer
    I should be able to mock external HTTP call

    Scenario: Mock external call
        Given a file named "behat.yml" with:
            """
            default:
                extensions:
                    Rezzza\MocoBehatExtension\MocoExtension:
                        json_file: features/fixtures.yml
                        hostname: 127.0.0.1
                        port: 9999
                    Rezzza\RestApiBehatExtension\Extension:
                        rest:
                            base_url: http://127.0.0.1:9999
                            adaptor_name: curl
                suites:
                    default:
                        contexts:
                            - FeatureContext
                            - Rezzza\RestApiBehatExtension\RestApiContext
                            - Rezzza\MocoBehatExtension\MocoContext
            """
        And a file named "features/bootstrap/FeatureContext.php" with:
            """
            <?php
            use Behat\Behat\Context\Context;
            use Rezzza\MocoBehatExtension\MocoWriter;

            class FeatureContext implements Context
            {
                private $mocoWriter;

                public function __construct(MocoWriter $mocoWriter)
                {
                    $this->mocoWriter = $mocoWriter;
                }

                /**
                 * @Given the url :requestUrl returns :responseContent
                 */
                public function mockHttpCall($requestUrl, $responseContent)
                {
                    $this->mocoWriter->mockHttpCall(
                        ['uri' => $requestUrl],
                        ['status' => 200, 'text' => $responseContent]
                    );
                    $this->mocoWriter->writeForMoco();
                }
            }
            """
        And a file named "features/call_moco.feature" with:
            """
            Feature: Call Moco
                In order to mock external call
                As a feature runner
                I need to call moco

                @moco
                Scenario: Call moco
                    Given the url "/coucou/hibou" returns "Hey you, this is crazy"
                    When I send a GET request to "/coucou/hibou"
                    Then print response
            """
        And I start moco
        When I run behat "features/call_moco.feature"
        Then it should pass with:
            """
            Hey you, this is crazy
            """
