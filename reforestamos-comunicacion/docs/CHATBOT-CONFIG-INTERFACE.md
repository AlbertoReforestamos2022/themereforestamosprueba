# ChatBot Configuration Interface - Task 21.3

## Overview

This document describes the enhanced configuration interface for the ChatBot system implemented in task 21.3.

## Requirements Implemented

- **Requirement 10.2**: Admin interface for configuring chatbot responses
- **Requirement 10.7**: Enable/disable chatbot globally

## Features Implemented

### 1. General Configuration

- **Enable/Disable Toggle**: Global switch to activate or deactivate the chatbot on the entire site
- **Configuration Tips**: Helpful tips displayed at the top of the page to guide administrators

### 2. Response Management

#### Basic Response Configuration
- **Add/Remove Responses**: Dynamic table allowing administrators to add or remove response patterns
- **Pattern Matching**: Support for multiple keywords separated by "|" (e.g., "hola|buenos días|buenas tardes")
- **Response Text**: Textarea for each response with proper sanitization

#### Import/Export Functionality
- **Export Responses**: Download current responses as JSON file for backup or sharing
- **Import Responses**: Upload previously exported JSON to restore or migrate configurations
- **Date-stamped Exports**: Exported files include date in filename for easy organization

### 3. Conversation Flow Management

#### Flow Display
- **Flow Cards**: Visual cards showing each conversation flow with:
  - Flow name
  - Trigger keywords
  - Number of steps
  - Expandable details view

#### Flow Actions
- **Create New Flow**: Button to create basic conversation flows with name and trigger keywords
- **Edit Flow**: Placeholder for future advanced editing (currently shows informational message)
- **Delete Flow**: Remove custom flows with confirmation
- **Reset to Defaults**: Restore all flows to default configuration

#### Flow Details
- **Step-by-step View**: Expandable details showing:
  - Each step's message
  - Available options and their keywords
  - Response for each option
  - Flow completion message

### 4. Statistics Dashboard

- **Total Conversations**: Count of unique conversation sessions
- **Total Messages**: Count of all messages exchanged
- **Today's Messages**: Count of messages from current day

## Technical Implementation

### Files Modified

1. **reforestamos-comunicacion/admin/views/chatbot-config.php**
   - Enhanced UI with tips section
   - Added import/export controls
   - Added flow management buttons (Create, Edit, Delete, Reset)
   - Added import modal
   - Enhanced JavaScript for all new features
   - Improved CSS styling

2. **reforestamos-comunicacion/includes/class-chatbot.php**
   - Added AJAX hooks for flow management
   - Implemented `ajax_save_flow()` method
   - Implemented `ajax_delete_flow()` method
   - Implemented `ajax_reset_flows()` method

### AJAX Endpoints

1. **save_chatbot_flow**
   - Action: Create or update a conversation flow
   - Security: Nonce verification + capability check
   - Parameters: flow_id, flow_data (JSON)

2. **delete_chatbot_flow**
   - Action: Remove a conversation flow
   - Security: Nonce verification + capability check
   - Parameters: flow_id

3. **reset_chatbot_flows**
   - Action: Restore default flows
   - Security: Nonce verification + capability check
   - Parameters: None

### Security Features

- **Nonce Verification**: All AJAX requests verify nonces
- **Capability Checks**: Only users with `manage_options` can modify configuration
- **Input Sanitization**: All user inputs are sanitized using WordPress functions
- **Output Escaping**: All outputs are properly escaped

## Usage Guide

### Configuring Responses

1. Navigate to **Reforestamos Comunicación > ChatBot Config**
2. Enable/disable the chatbot using the checkbox
3. Add responses by clicking "Agregar Respuesta"
4. Enter keywords separated by "|" in the pattern field
5. Enter the bot's response in the textarea
6. Click "Guardar Configuración" to save

### Managing Flows

1. View existing flows in the "Flujos de Conversación" section
2. Click "Ver detalles del flujo" to expand and see all steps
3. Create new flows with "Crear Nuevo Flujo"
4. Delete custom flows with the trash icon
5. Restore defaults with "Restaurar Flujos Predeterminados"

### Import/Export

**To Export:**
1. Click "Exportar Respuestas"
2. JSON file downloads automatically with current date

**To Import:**
1. Click "Importar Respuestas"
2. Paste JSON content in the modal
3. Click "Importar"
4. Save configuration to persist changes

## Future Enhancements

The following features are planned for future implementation:

1. **Advanced Flow Editor**: Visual flow builder with drag-and-drop interface
2. **Response Analytics**: Track which responses are most used
3. **A/B Testing**: Test different responses to optimize engagement
4. **Multi-language Support**: Configure responses per language
5. **AI Integration**: Optional AI-powered responses for unmatched queries
6. **Flow Templates**: Pre-built flow templates for common scenarios

## Testing

To test the configuration interface:

1. Access the admin page at `/wp-admin/admin.php?page=reforestamos-chatbot`
2. Verify all buttons and controls work correctly
3. Test adding/removing responses
4. Test import/export functionality
5. Test flow management (create, delete, reset)
6. Verify changes persist after saving
7. Test the chatbot on frontend to confirm configuration applies

## Compatibility

- WordPress: 6.0+
- PHP: 7.4+
- Browsers: Modern browsers with JavaScript enabled
- Dependencies: jQuery (included with WordPress)

## Support

For issues or questions about the chatbot configuration:
- Check the tips section at the top of the config page
- Review conversation logs for debugging
- Contact the development team for advanced customization
