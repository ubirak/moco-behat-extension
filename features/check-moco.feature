Feature: Check moco
    In order to not lost time to understand why a test fail
    As a developer
    I should be able to know when moco is not running

    Scenario: Moco is not started
        Given a file named "behat.yml" with:
            """
            default:
                extensions:
                    Ubirak\MocoBehatExtension\MocoExtension:
                        json_file: features/fixtures.yml
                        hostname: 127.0.0.1
                        port: 9997
                    Ubirak\RestApiBehatExtension\Extension:
                        rest:
                            base_url: http://127.0.0.1:9997
                            store_response: true
                suites:
                    default:
                        contexts:
                            - Ubirak\RestApiBehatExtension\RestApiContext
                            - Ubirak\MocoBehatExtension\MocoContext
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
            Cannot connect to moco on 127.0.0.1 : Connection refused. Ensure to run moco with "bin/moco start -p 9997 -c features/fixtures.yml"
            """
