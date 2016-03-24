# Moco Extension for Behat 3

[Moco](https://github.com/dreamhead/moco) is a stub server we used to use in test env.

Here is a small extension to make its usage more friendly.

## Usage

```yml
default:
    extensions:
        Rezzza\MocoBehatExtension\MocoExtension:
            json_file: features/fixtures.yml
    suites:
        default:
            contexts:
                - Rezzza\MocoBehatExtension\MocoContext:
                    mocoIp: 127.0.0.1
                    mocoPort: 9997
```

Then you just need to add `MocoWriter` as an argument of your context.

[See tests](features) for more details
