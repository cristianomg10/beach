# Validation
Classes for validation were developed to help comparison. These classes implement IValidation interface.

## Available methods
- HoldoutValidation;
- CrossValidation.

## Example of input data
In this case, the label is the #4 index (the last). 

**Be careful** with the input data. If it's transposed, wrong, or with different numbers of fields, it will give you wrong answers, weird exceptions and so on. If it happens, try to treat the data before.
```
[5.1, 3.5, 1.4, 0.2, 0]
[4.9, 3.0, 1.4, 0.2, 0]
[4.7, 3.2, 1.3, 0.2, 0]
[4.6, 3.1, 1.5, 0.2, 0]
[5.0, 3.6, 1.4, 0.2, 0]
[5.4, 3.9, 1.7, 0.4, 0]
[4.6, 3.4, 1.4, 0.3, 0]
[5.0, 3.4, 1.5, 0.2, 0]
```