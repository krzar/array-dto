# PHP Array Objects

![license mit](https://badgen.net/github/license/krzar/php-array-objects)
![release](https://badgen.net/github/release/krzar/php-array-objects)
![last commit](https://badgen.net/github/last-commit/krzar/php-array-objects)

This package allows you to generate object based on array data.

This can be useful for integrations with some APIs, for example.

## Supports

| Package Version | PHP Version    | Supported          |
|-----------------|----------------|--------------------|
| 2.x             | 8.1 &#124; 8.2 | :white_check_mark: |
| 1.x             | 8.0            | :x:                |

## Installation

```bash
composer require krzar/php-array-objects
```

### For PHP 8.0

```bash
composer require krzar/php-array-objects:^1.2
```

## Usage

### Simple object

```php
use KrZar\PhpArrayObjects\ArrayObject;

class UserData extends ArrayObject {
    public string $name;
    public string $email;
    public int $age;
    public float $money;
    public bool $isActive = false;
    public array $roles = [];
}
```

To create this object from array call:

```php
$data = [
    'name' => 'Test',
    'email' => 'test@test.com',
    'age' => 99,
    'money' => 1520.50,
    'isActive' => true,
    'roles' => ['ADMIN']
];

UserData::create($data);
```

If any parameter is not passed no value will be assigned, so a default value should be established for such cases.

### Nested object

```php
class CompanyData extends ArrayObject {
    public string $name;
    public string $city;
    public string $street;
}

class UserData extends ArrayObject {
    public string $name;
    public string $email;
    public int $age;
    public float $money;
    public bool $isActive = false;
    public array $roles = [];
    public CompanyData $company;
}
```

To create this object from array call:

```php
$data = [
    'name' => 'Test',
    'email' => 'test@test.com',
    'age' => 99,
    'money' => 1520.50,
    'isActive' => true,
    'roles' => ['ADMIN'],
    'company' => [
        'name' => 'Test Company',
        'city' => 'Test',
        'street' => 'Test Street 1'
    ]   
];

UserData::create($data);
```

### Nested object multidimensional

If you want to create array of objects you need to configure this with `$arrayMap` property.

```php
class UserData extends ArrayObject {
    public string $name;
    public string $email;
    public int $age;
    public float $money;
    public bool $isActive = false;
    public array $roles = [];
    public CompanyData $company;
    public array $children;
    
    protected array $arrayMap = [
        'children' => UserData:class
    ];
}
```

To create this object from array call:

```php
$data = [
    'name' => 'Test',
    'email' => 'test@test.com',
    'age' => 99,
    'money' => 1520.50,
    'isActive' => true,
    'roles' => ['ADMIN'],
    'company' => [
        'name' => 'Test Company',
        'city' => 'Test',
        'street' => 'Test Street 1'
    ],
    'children' => [
        [
            'name' => 'Test 2',
            'email' => 'test2@test.com',
            'age' => 98,
            'money' => 2400,
            'isActive' => true,
            'roles' => ['MODERATOR']
        ]       
    ]       
];

UserData::create($data);
```

### Union types

You can use union types in objects, but with some restrictions:

- built-in types can be combined in any way
- ArrayObject type can be combined only with build-in types, not with other ArrayObject

For example:

```php
public CompanyData|string $company;
```

`CompanyData` will be created if a `company` index in array is array type.

### Names mapping

You can map names of parameters using `$namesMap` array:

```php
class UserData extends ArrayObject {
    public string $name;
    public string $email;
    public int $age;
    public float $money;
    public bool $isActive = false;
    public array $roles = [];
    public CompanyData $company;
    public array $children;
    
    protected array $arrayMap = [
        'children' => UserData:class
    ];
    
    protected array $namesMap = [
        'isActive' => 'is_active'
    ]
}
```

### Types mapping

You can map types of parameters using `$typesMap` array:

```php
class UserData extends ArrayObject {
    public string $name;
    public string $email;
    public int $age;
    public float $money;
    public bool $isActive = false;
    public array $roles = [];
    public CompanyData $company;
    public array $children;
    
    protected array $arrayMap = [
        'children' => UserData:class
    ];
    
    protected array $namesMap = [
        'isActive' => 'is_active'
    ]
    
    protected array $typesMap = [
        'age' => 'int'
    ]
}
```

For example age for user in api is a string, but we want to map this to int.

You can also use `typesMap` method, this allows you tu use Closure.

```php
    protected function typesMap(): array {
        return [
            'age' => fn(string $value, array $data) => (int) $value
        ]
    }
```

First argument of closure is a value of property, second is full data array.
