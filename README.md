## Wtngrm
---
**master:** [![Build Status master](https://api.travis-ci.org/desyncr/wtngrm.png?branch=master)](http://travis-ci.org/desyncr/wtngrm) |
**development:** [![Build Status development](https://api.travis-ci.org/desyncr/wtngrm.png?branch=development)](http://travis-ci.org/desyncr/wtngrm)

Zend2 base module for various queue job services. Currently supporting [Gearman][4] and [Beanstalk][5].

## Installation

Recommended installation method is through composer:

    composer require desyncr/wtngrm dev-master

## Configuration

Register the module in the main application: `config/application.config.php`

    'modules' => array(
        ...
        'Desyncr\\Wtngrm'
        ...
    )

## Feedback

If you'd like to contribute to the project or file a bug or feature request, please visit [the project page][1].

## License

The project is licensed under the [GNU GPL v3][2] ([tldr][3]) license. Which means you're allowed to copy, edit, change, hack, use all or any part of this project *as long* as all of the changes and contributions remains under the same terms and conditions.

  [1]: https://github.com/desyncr/wtngrm/
  [2]: http://www.gnu.org/licenses/gpl.html
  [3]: http://www.tldrlegal.com/license/gnu-general-public-lic
  [4]: http://gearman.org/
  [5]: http://kr.github.io/beanstalkd/
