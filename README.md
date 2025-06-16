# StupidCMS - User Guide

A simple content management system that uses Markdown files and PHP templates.

## 🏗️ Architecture

StupidCMS follows a simple, flat structure:
- **src/** - All PHP classes in one directory
- **content/** - Your markdown files and assets  
- **templates/** - PHP template files
- **public/** - Web-accessible files (CSS, JS, media)

All StupidCMS classes use the simple `StupidCMS\` namespace - no subdirectories or complex hierarchies.

## 📁 Content Structure

Your content lives in the `content/` directory using Markdown files. Each content directory may have exactly one markdown file.

```
content/
├── index.md          # Homepage
├── cv/
│   └── cv.md         # CV page  
└── work/
    ├── work.md       # Work overview page
    ├── artasio/
    │   └── project.md
    └── blender/
        └── project.md
```

## ✍️ Writing Content

Create `.md` files with frontmatter (metadata) at the top. You can add as many custom fields as you need, but only one markdown body per file.

```markdown
---
title: My Page Title
published: true
custom_field: your custom value
---

# Your Content Here

Write your content in Markdown format.
```

### Reserved Frontmatter Fields

These fields have special meaning in StupidCMS:

- `title`: Page title (defaults to capitalized slug if not provided)
- `published`: Set to `true` to make page visible (defaults to `false`)
- `date`: Publication date (auto-added if not provided, format: Y-m-d)
- Fields with `type: img`: Complex image objects with src/alt properties

You can add any additional custom fields you need - they'll be accessible in your templates.

## 🎨 Templates

Templates are automatically chosen based on your content filename:

- `index.md` → `templates/index.php`
- `work.md` → `templates/work.php`  
- `project.md` → `templates/project.php`
- `cv.md` → `templates/cv.php`
- Fallback: `templates/default.php`

### Template Variables & Functions

In your templates, you have access to:

- `$foo` - The current content object with all frontmatter fields and methods

### Content Properties & Methods

#### Accessing Frontmatter Fields
```php
<?= $foo->title ?>           // Access any frontmatter field directly
<?= $foo->published ?>       // Boolean - is content published?
<?= $foo->date ?>            // Publication date
<?= $foo->slug ?>            // Current page URL slug
<?= $foo->customField ?>     // Any custom frontmatter field
```

#### Core Methods
```php
$foo->getBody()              // Returns: string - parsed markdown as HTML
$foo->render()               // Returns: string - full page with template applied
$foo->children()             // Returns: array - list of child pages (see below)
$foo->root()                 // Returns: Content - the homepage content object
```

#### Child Content Access
```php
$foo->child('artasio')       // Returns: Content|null - content from artasio/ directory
$foo->child('any-folder')    // Returns: Content|null - content from any-folder/ directory
```

#### children() Array Structure
```php
$foo->children() returns:
[
    [
        'slug' => 'work/artasio',        // Full path for identification
        'url' => '/work/artasio',        // Browser URL
        'title' => 'Artasio',           // Page title
        'name' => 'artasio'             // Directory name (for $foo->child())
    ],
    // ... more children
]
```

### Template Partials

Use PHP includes to include other templates:

```php
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/menu.php'; ?>

<div class="content">
    <?= $foo->getBody() ?>
</div>

<?php include __DIR__ . '/partials/foot.php'; ?>
```

Included templates have access to the same `$foo` object.

### Dynamic Content Loading

Access child content using the `child()` method:

```php
<!-- In any template -->
<?php 
$children = $foo->children();          // Get all child items
$firstChild = $children[0];            // Get first child
$childContent = $foo->child($firstChild['name']);  // Load child content by directory name
?>
```

## 🖼️ Images & Assets

Place images next to your content files. They'll be automatically copied to `/public/media/`.

```markdown
![Alt text](image.jpg)              <!-- Basic image -->
![small:Alt text](image.jpg)        <!-- Small image -->
![medium:Alt text](image.jpg)       <!-- Medium image -->
![large:Alt text](image.jpg)        <!-- Large image -->
![full:Alt text](image.jpg)         <!-- Full width -->
```

For frontmatter images:
```yaml
---
featured_img:
  type: img
  src: hero.jpg
  alt: "Hero image"
---
```

## 🚀 Quick Start

1. **Add a new page**: Create `content/my-page.md`
2. **Add frontmatter**: Set title, published status, and any custom fields
3. **Write content**: Use Markdown syntax  
4. **Create template** (optional): Add `templates/my-page.php` to match your filename
5. **Test**: Visit `/my-page` in your browser

## 📂 Adding Content Sections

To add any new content section:

1. Create folder: `content/my-section/`
2. Add content: `content/my-section/section.md` (or any `.md` file)
3. Add child items: `content/my-section/item-name/item.md`  
4. Add assets: Place images and files in the same folders
5. Set published: `published: true` in frontmatter

Content will be automatically accessible via `$foo->child('directory-name')` in templates.

## 🎨 ASCII Art

Generate ASCII art in templates using the convenient global helper:


```php
<?php 
use StupidCMS\AsciiArt;
echo AsciiArt::convert('Hello World', 'Electronic');        // Plain text output
echo AsciiArt::convertWithSpans('Hello World', 'Electronic'); // HTML with accessibility spans
echo AsciiArt::ascii('burger');                             // Predefined symbols
echo AsciiArt::symbolWithSpan('burger', 'Menu');            // Symbol with accessibility span
?>
```

**Available fonts:** Electronic, DiamFont  
**Available symbols:** burger