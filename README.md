# ElasticaBundle

This is a simple Symfony wrapper for [elastica](http://elastica.io/). It allows you to configure elastica as a service in Symfony.

## Installation

Install is done via `composer`:

```zsh
$ composer require wikibusiness/elastica-bundle
```

Add the bundle to your kernel:

```php
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new WB\ElasticaBundle\WBElasticaBundle(),
        );
        // ...
    }
}
// ...
```

Configure the bundle:

```yaml
# app/config/config.yml
wb_elastica:
    servers:
        main:
            host: 127.0.0.1
            port: 9200
```

Where `main` is a grouping.

If you want to use Elastica in cluster mode, the config section should look something like this:

```yaml
# app/config/config.yml
wb_elastica:
    servers:
        host_1:
            host: 127.0.0.1
            port: 9200
        host_2:
            host: 127.0.0.1
            port: 9201
```

All done, you can now access the service via the service `wb_elastica.client` like this:

```php
use Elastica\Search;

$client = $container->get('wb_elastica.client');
$search = new Search($client);
...
```
