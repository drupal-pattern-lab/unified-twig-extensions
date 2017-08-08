# Unified Twig Extensions

Share Pattern Lab's custom twigs functions, filters and tags with Drupal 8. Huzzah!


## Getting Started
Simply add to Drupal and enable the `
Unified Twig Extensions` module on the `admin/modules` page to get started.


## Note on Paths
Note: currently looks for compatible extensions in your current active D8 theme path + either the 'pattern-lab/source/_twig-components' or 'source/_twig-components' folders. @TODO: allow users to customized / override this!


## Included Examples
I'm including a couple example twig extensions to add to your existing Pattern Lab-enabled theme to get started:
  1. `example/_twig-components/functions/link.function.php` --> example of having Drupal ignore a PL Twig extension given the link function already exists in Drupal.

  2. `example/_twig-components/tags/grid.tag.php` and `example/_twig-components/tags/cell.tag.php` --> example of a custom Twig tag that abstracts away some of the markup involved in an ITCSS-based grid system.

  To test this out, try adding these two custom Twig tags to your theme's existing _twig-components folder and try adding the following HTML (to both PL's twig templates and/or a Drupal template):

```twig
{% grid 'o-grid--large' %}
  {% cell 'u-1/1 u-1/2@small u-2/3@medium' %}
    Grid cell
  {% endcell %}

  {% cell 'u-1/1 u-1/2@small u-1/3@medium' %}
    Grid cell
  {% endcell %}
{% endgrid %}
```

Everything should be working as expected if you don't encounter any errors and the following HTML gets output (rendered of course):

```html
<div class="o-grid o-grid--large">
  <div class="o-grid__item u-1/1 u-1/2@small u-2/3@medium">
    Grid cell
  </div>

  <div class="o-grid__item u-1/1 u-1/2@small u-1/3@medium">
    Grid cell
  </div>
</div>
  ```
