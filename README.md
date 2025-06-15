# StupidCMS Developer Documentation

A lightweight, file-based content management system built in PHP with markdown content and template-based rendering.

## Architecture Overview

StupidCMS follows a clean architecture with clear separation of concerns:

- **Content Layer**: File-based content management using markdown files with YAML frontmatter
- **Template Layer**: PHP templates with variable injection
- **Routing Layer**: Simple routing with fallback to content-based URLs
- **Utility Layer**: Markdown parsing, image handling, and field processing

## Core Components

### Application Bootstrap (`src/Core/Application.php`)
The main application class that initializes all services and handles the request lifecycle:
- Dependency injection container
- Service initialization
- Error handling
- Request routing

### Content Management

#### ContentService (`src/Content/ContentService.php`)
High-level content operations:
- `getContentBySlug(string $slug)`: Retrieve content by URL slug
- `getChildren(string $slug)`: Get child pages for navigation
- `getImages(string $slug)`: Get images in content directory

#### ContentBuilder (`src/Content/ContentBuilder.php`)
Low-level content building and parsing:
- Markdown file resolution
- Frontmatter parsing
- Template determination
- Field processing and sanitization

#### Content (`src/Content/Content.php`)
Content model with properties:
- `body`: Rendered HTML content
- `slug`: URL identifier
- `published`: Publication status
- `template`: Template name
- `customFields`: Additional metadata

### Routing System

#### Router (`src/Http/Router.php`)
Simple routing with content fallback:
- Exact route matching for special endpoints
- Content-based routing for pages
- Default route: `/work/project` handled by ProjectController

#### Controllers
- **PageController**: Standard page rendering
- **ProjectController**: Project-specific functionality with HTMX support

### Template System

#### TemplateEngine (`src/Template/TemplateEngine.php`)
PHP-based templating with:
- Variable escaping for security
- Template existence checking
- Content proxy integration

#### Templates (`templates/`)
- `default.php`: Fallback template
- `index.php`: Homepage template
- `work.php`: Work portfolio with HTMX navigation
- `project.php`: Individual project pages
- `cv.php`: CV/resume template

## Content Structure

### File Organization
```
content/
├── index.md              # Homepage
├── cv/
│   └── cv.md            # CV page
└── work/
    ├── work.md          # Work portfolio index
    ├── artasio/
    │   ├── project.md   # Project content
    │   └── *.png        # Project images
    └── blender/
        ├── project.md
        └── *.png
```

### Frontmatter Format
```yaml
---
title: "Page Title"
published: true
navigation:
  - url: "/"
    name: "Home"
featured_img:
  type: img
  src: image.png
  alt: "Alt text"
---
```

### URL Mapping
- `/` → `content/index.md`
- `/work` → `content/work/work.md`
- `/work/artasio` → `content/work/artasio/project.md`
- `/cv` → `content/cv/cv.md`

## Image Processing

### ImageHandler (`src/Util/ImageHandler.php`)
- Automatic image path resolution
- Markdown image processing with size classes
- Media directory copying for public access

### Image Syntax
```markdown
![small:Alt text](image.png)    # Adds 'small' CSS class
![medium:Alt text](image.png)   # Adds 'medium' CSS class
![large:Alt text](image.png)    # Adds 'large' CSS class
```

## Field Processing

### FieldProcessor (`src/Util/FieldProcessor.php`)
- Sanitization of frontmatter fields
- Default value injection
- Special field type handling

### Special Field Types
- `type: img` - Image fields with automatic path resolution
- Fields ending in `_img` - Automatic image processing

## Development Workflow

### Local Development
- Server runs on `localhost:3003`
- Use `curl` for testing endpoints
- CSS built with Tailwind 4 (`index.css` → `main.css`)

### Adding New Content
1. Create markdown file in appropriate `content/` subdirectory
2. Add YAML frontmatter with `title` and `published: true`
3. Images go in same directory, referenced relatively
4. Template determined by filename or falls back to `default.php`

### Creating Templates
1. Add PHP file to `templates/` directory
2. Use `$foo` variable for content access
3. Include partials from `templates/partials/`
4. Follow existing template structure for consistency

### Custom Controllers
1. Extend `BaseController`
2. Register routes in `Router::registerRoutes()`
3. Access content via `$this->contentService`
4. Render via `$this->renderTemplate()`

## Utilities

### MarkdownParser (`src/Util/MarkdownParser.php`)
- ParsedownExtra integration
- Safe mode enabled
- Image processing integration

### FileLoader (`src/Util/FileLoader.php`)
- Markdown file discovery
- Frontmatter parsing
- Directory operations

### AsciiArt (`src/Util/AsciiArt.php`)
- ASCII art generation for headers
- Multiple font support

## Security Features
- Safe mode markdown parsing
- Output escaping in templates
- Frontmatter sanitization
- Published status filtering

## Frontend Integration
- HTMX for dynamic content loading
- Alpine.js for interactivity
- Tailwind CSS for styling
- Custom fonts and media handling

## Production Deployment
- Production URL: `https://cyrillappert.ch/`
- Static asset serving from `public/`
- Media files copied to `public/media/`
- Error logging to `public/error.log`