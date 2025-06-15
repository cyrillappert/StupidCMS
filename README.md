# StupidCMS - User Guide

A simple content management system that uses Markdown files and PHP templates.

## 📁 Content Structure

Your content lives in the `content/` directory using Markdown files:

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

Create `.md` files with frontmatter (metadata) at the top:

```markdown
---
title: My Page Title
published: true
template: default
---

# Your Content Here

Write your content in Markdown format.
```

### Key Frontmatter Fields

- `title`: Page title
- `published`: Set to `true` to make page visible
- `template`: Which template to use (optional, auto-detected from filename)

## 🎨 Templates

Templates are PHP files in the `templates/` directory:

- `index.php` - Homepage template
- `work.php` - Work/portfolio overview
- `project.php` - Individual project pages
- `cv.php` - CV/resume template
- `default.php` - Fallback template

### Template Variables

In your templates, you have access to:

- `$foo` - The current content object
- `$currentSlug` - Current page URL slug

### Content Object Methods

```php
<?= $foo->title ?>           <!-- Page title -->
<?= $foo->getBody() ?>       <!-- Rendered markdown content -->
<?= $foo->children() ?>      <!-- Array of child pages -->
<?= $foo->artasio() ?>       <!-- Load specific child content -->
```

### Dynamic Content Loading

The system automatically creates methods based on your folder structure:

```php
<!-- In work.php template -->
<?php 
$children = $foo->children();          // Get all work projects
$firstProject = $children[0];          // Get first project
$projectContent = $foo->{$firstProject['name']}();  // Load project content dynamically
?>
```

## 🚀 Quick Start

1. **Add a new page**: Create `content/my-page.md`
2. **Add frontmatter**: Set title, published status
3. **Write content**: Use Markdown syntax  
4. **Create template** (optional): Add `templates/my-page.php`
5. **Test**: Visit `/my-page` in your browser

## 📂 Adding Projects

To add a new work project:

1. Create folder: `content/work/my-project/`
2. Add content: `content/work/my-project/project.md`
3. Add images: Place in same folder
4. Set published: `published: true` in frontmatter

The project will automatically appear in the work section and be accessible via `$foo->{'my-project'}()` in templates.