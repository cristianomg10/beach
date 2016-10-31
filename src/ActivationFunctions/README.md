# Activation Functions' Folder

## About
This folder contains customised Activation Functions. By now, it has Sigmoidal (SigmoidalFunction) and Step (StepFunction).

## How to add mine?
If you want to create your own activation function, you just need to:

- Create a new file in this folder;
- Implement IActivationFunction.

### Example

```php

<?php

 namespace App\ActivationFunctions;
 
 use App\ActivationFunctions\IActivationFunction;

 class MyFunction implements IActivationFunction
 {
     public function activationFunction($value)
     {
         return $value * -1;
     }
 }
 
 ```
