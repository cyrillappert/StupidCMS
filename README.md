# StupidCMS - User Guide

A simple content management system that uses Markdown files and PHP templates.

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
- Fields ending with `_img`: Automatically processed as image fields
- Fields with `type: img`: Complex image objects with src/alt properties

You can add any additional custom fields you need - they'll be accessible in your templates.

## 🎨 Templates

Templates are automatically chosen based on your content filename:

- `index.md` → `templates/index.php`
- `work.md` → `templates/work.php`  
- `project.md` → `templates/project.php`
- `cv.md` → `templates/cv.php`
- Fallback: `templates/default.php`

### Template Variables

In your templates, you have access to:

- `$foo` - The current content object
- `$currentSlug` - Current page URL slug (accessible via `$foo->slug`)

### Content Object Methods

```php
<?= $foo->title ?>           <!-- Access any frontmatter field -->
<?= $foo->getBody() ?>       <!-- Rendered markdown content -->
<?= $foo->render() ?>        <!-- Render content with template -->
<?= $foo->children() ?>      <!-- Array of child pages -->
<?= $foo->root() ?>          <!-- Root content object -->
<?= $foo->artasio() ?>       <!-- Load specific child content -->
```

### Dynamic Content Loading

The system automatically creates methods based on your folder structure:

```php
<!-- In any template -->
<?php 
$children = $foo->children();          // Get all child items
$firstChild = $children[0];            // Get first child
$childContent = $foo->{$firstChild['name']}();  // Load child content dynamically
?>
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

Content will be automatically accessible via `$foo->{'section-name'}()` in templates.