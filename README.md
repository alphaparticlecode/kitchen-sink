# Kitchen Sink
A WordPress plugin that generates a "kitchen sink" page with examples of Gutenberg block implementations.

------

When building a site with many custom blocks or lots of pages, it can be difficult to remember all the variations or places they appear on the site. With Kitchen Sink, you can generate a kitchen sink page that has at least one variation of each and every block in use on the site, with the ability to add specific versions of blocks from a given page as well. This gives you a comprehensive view of all the block possibilities of your existing site, so you can know what you have available when designing new pages or modifying existing ones.

Here is an example of a [kitchen sink page](https://get.foundation/sites/docs-v5/components/kitchen_sink.html) from the Foundation frontend framework.

## Features

### Generate on-demand

Any administrator of the site can generate or re-generate the kitchen sink page at any time from a button right in your WordPress dashboard. When generating the page, the plugin will crawl your site and look at all the blocks currently available and in use to generate the most up-to-date version of the kitchen sink page.

### Customize to fit your needs

Once the page is generated, you can customize it just like any other page or post. Since all the examples of blocks are saved right in the post content, you can edit and change them as necessary. If a block needs a bit more explanation, add a paragraph of explainer text below the block name! Anything you can do with the Block Editor, you can do on your kitchen sink page.

**NOTE: These customizations will only last as long as you don't regenerate the page using the widget on the dashboard. Once you regenerate the page, it will revert back to being just a collection of your blocks.**

### Comprehensive overview of blocks

Kitchen Sink will generate a page with one instance of every block in use on the site. This includes core blocks (heading, paragraph, image, etc), any blocks added by third-party plugins and any blocks added to your theme, whether they're registered using ACF or as React blocks.

### Ability to add specific instances of blocks

If you've built a specific instance of a block on a page where the options are configured just right, you can copy that exact version of your block over onto the kitchen sink page with a single click, right from the sidebar in the block editor.

### View blocks in context

Each instance of a block that shows up on the kitchen sink page will also include a link to exactly where that specific block appears, so you can see how it fits in with all of the other blocks on that page or post. 

## Contributions

Contributions are welcome! This project is still in the very early phases and being rolled out on Alpha Particle client sites, which will largely inform the future direction of development. If you have a use case for this plugin that isn't currently supported but want to use with your clients, [open an issue](https://github.com/alphaparticlecode/kitchen-sink/issues/new) and let's talk about it.

## Questions? Issues?

Feel free to [open an issue](https://github.com/alphaparticlecode/kitchen-sink/issues/new) or send an email to [hello@alphaparticle.com](mailto:hellp@alphaparticle.com). Please provide as much information as possible regarding what you're trying to achieve and or add to the project. If you've encountered a bug, please include the version of WordPress you are running and as much information as possible about what you were doing that led to the bug you're encountering.

