Feature: Check moco
    In order to not lost time to understand why a test fail
    As a developer
    I should be able to know when moco is not running

    Scenario: Moco is not started
        Given a file named "behat.yml" with:
            """
            default:
                extensions:
                    Rezzza\MocoBehatExtension\MocoExtension:
                        json_file: features/fixtures.yml
                    Rezzza\RestApiBehatExtension\Extension:
                        rest:
                            base_url: http://127.0.0.1:9997
                            adaptor_name: curl
                suites:
                    default:
                        contexts:
                            - Rezzza\RestApiBehatExtension\RestApiContext:
                            - Rezzza\MocoBehatExtension\MocoContext:
                                mocoIp: 127.0.0.1
                                mocoPort: 9997
            """
        And a file named "features/call_moco.feature" with:
            """
            Feature: Call Moco
                In order to mock external call
                As a feature runner
                I need to call moco

                @moco
                Scenario: Call moco
                    When I send a GET request to "/coucou"
            """
        When I run behat "-f progress features/call_moco.feature"
        Then it should fail with:
            """
            You should run moco by "bin/moco start -p 9997 -c features/fixtures.yml"
            """
