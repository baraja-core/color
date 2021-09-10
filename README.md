PHP Color normalizer
====================

Normalize HTML colors.

Idea
----

On websites, we often need to manipulate the colours we get in different ways. For example, from the user or from another application layer.

This package provides a simple way to normalize the received color to a canonical shape.

When normalizing, you can choose to convert to short or long notation:

Examples:

```php
Color::normalize('#aaA'); // '#aaa'
Color::normalize('#abcd'); // '#abc'
Color::normalize('#aaAAaa'); // '#aaa'
Color::normalize('#aaA', Color::FORMAT_LONG); // '#aaaaaa'
```

A second parameter can be used for formatting, which has possible values:

- `Color::FORMAT_SHORT` prefers short notation (if possible)
- `Color::FORMAT_LONG` always returns a long notation
