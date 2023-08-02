# Config component

<hr />

## Three builder
build the three schema validation for the configuration files

- ### Usage
You can extend the default config schema by implementing the ```ConfigInterface```.

Then you can implement the method ```ConfigInterface->config(ThreeBuilder $builder): ThreeBuilder```

```php 
$builder
    ->root('config_key')
        ->children()
            ->scalarNode('node_name')
            ->integerNode('node_name')
        ->end()
    ->end()
;
```
- ### Nodes

    - scalarNode (int, float, boolean, string, null)
    - intNode
    - stringNode
    - booleanNode
    - floatNode
    - numberNode (int and float)
    - enumNode
    - arrayNode

- ### F