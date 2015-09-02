# Bootstrap Plugin

Injects Twitter Bootstrap CSS classes into FormHelper input elements. This plugin
requires the `goaop/framework` library to work.

# Usage

Use AOP framework as described in
[their documentation](https://github.com/goaop/framework),
create an application Kernal and attach the provided Aspect class to it:
`Bootstrap\Aspect\FormHelperAspect`.