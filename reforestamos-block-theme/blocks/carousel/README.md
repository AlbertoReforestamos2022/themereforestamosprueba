# Carousel Block

## Description
Image carousel/slider using Bootstrap 5 carousel component. Allows users to add multiple images with captions and configure autoplay settings.

## Attributes

### images (array)
- **Type:** Array of objects
- **Default:** `[]`
- **Description:** Array of image objects with the following structure:
  ```javascript
  {
    id: number,      // WordPress media ID
    url: string,     // Image URL
    alt: string,     // Alt text
    caption: string  // Image caption
  }
  ```

### autoplay (boolean)
- **Type:** Boolean
- **Default:** `true`
- **Description:** Whether the carousel should automatically cycle through slides

### interval (number)
- **Type:** Number
- **Default:** `5000`
- **Description:** Time in milliseconds between slide transitions (only applies when autoplay is enabled)

### showControls (boolean)
- **Type:** Boolean
- **Default:** `true`
- **Description:** Whether to show previous/next navigation arrows

### showIndicators (boolean)
- **Type:** Boolean
- **Default:** `true`
- **Description:** Whether to show slide position indicators at the bottom

## Features

- **Drag and Drop Reordering:** Images can be reordered using drag and drop in the editor
- **Image Captions:** Each image can have an optional caption that displays on the frontend
- **Responsive Design:** Carousel adapts to different screen sizes
- **Bootstrap 5 Integration:** Uses native Bootstrap 5 carousel component
- **Lazy Loading:** Images after the first slide use lazy loading for better performance
- **Accessibility:** Includes proper ARIA labels and keyboard navigation support

## Usage

1. Add the Carousel block to your page
2. Click "Select Images" to choose images from the media library
3. Drag and drop images to reorder them
4. Add captions to images (optional)
5. Configure settings in the Inspector Controls:
   - Toggle autoplay on/off
   - Adjust interval timing
   - Show/hide controls
   - Show/hide indicators

## Technical Details

### Dependencies
- `@dnd-kit/core`: For drag and drop functionality
- `@dnd-kit/sortable`: For sortable list implementation
- `@dnd-kit/utilities`: Utility functions for dnd-kit
- Bootstrap 5: For carousel component (loaded via CDN)

### Block Registration
The block is automatically registered via the dynamic block registration system in `inc/block-registration.php`.

### Styling
- Editor styles: Defined in `style.scss` under `.reforestamos-carousel-editor`
- Frontend styles: Defined in `style.scss` under `.reforestamos-carousel`
- Bootstrap carousel styles are inherited from Bootstrap 5 CDN

## Requirements Validation

This block satisfies the following requirements from the spec:
- **Requirement 2.2:** Custom block with block.json, edit.js, save.js, and style.scss
- **Requirement 2.3:** Block appears in editor with proper edit interface
- **Requirement 2.4:** Block renders correctly on frontend
- **Requirement 2.5:** Visual appearance matches between editor and frontend

## Browser Support
- Modern browsers with ES6 support
- Bootstrap 5 compatible browsers
- Drag and drop requires modern browser with pointer events support
