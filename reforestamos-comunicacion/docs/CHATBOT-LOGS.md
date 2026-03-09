# ChatBot Conversation Logs System

**Feature:** Conversation Logs Viewing Interface  
**Task:** 21.4 Implementar logging de conversaciones  
**Requirement:** 10.6 - Log all chatbot conversations for analysis

## Overview

The ChatBot Conversation Logs system provides administrators with a comprehensive interface to view, analyze, filter, and export all chatbot conversations. This feature enables data-driven insights into user interactions and helps improve chatbot responses.

## Features

### 1. Statistics Dashboard

The logs page displays key metrics at the top:

- **Total Conversaciones**: Number of unique chat sessions
- **Total Mensajes**: Total number of messages exchanged
- **Mensajes Hoy**: Messages received today
- **Promedio Mensajes/Sesión**: Average messages per conversation

### 2. Conversation Logs Table

Displays all logged conversations with:
- **Fecha/Hora**: Timestamp of each message
- **Sesión**: Unique session identifier (truncated for display)
- **Mensaje del Usuario**: User's message in a blue-bordered box
- **Respuesta del Bot**: Bot's response in a green-bordered box

### 3. Filtering and Search

Multiple filter options:
- **Text Search**: Search within user messages and bot responses
- **Session Filter**: View all messages from a specific conversation
- **Date Range**: Filter by date (from/to)
- **Combined Filters**: Apply multiple filters simultaneously

### 4. Pagination

- Displays 50 logs per page
- Navigation controls for browsing through pages
- Shows total item count

### 5. Export to CSV

Export conversation logs for external analysis:
- Downloads as CSV file with timestamp
- Includes all log data: ID, Session, User Message, Bot Response, Date/Time
- UTF-8 encoded for proper character support
- Compatible with Excel and Google Sheets

## Usage

### Accessing the Logs

1. Log in to WordPress admin
2. Navigate to **Comunicación** → **Logs de ChatBot**
3. View the statistics and conversation logs

### Viewing a Complete Conversation

1. Find a conversation in the logs table
2. Click the **"Ver conversación"** button next to the session ID
3. The page will filter to show all messages from that session

### Searching Conversations

1. Enter a search term in the search box
2. Click **"Filtrar"**
3. Results will show messages containing the search term
4. Click **"Limpiar filtros"** to reset

### Filtering by Date

1. Select a start date in the **"Desde"** field
2. Select an end date in the **"Hasta"** field
3. Click **"Filtrar"**
4. View conversations within that date range

### Exporting Data

1. Scroll to the **"Exportar Datos"** section
2. Ensure **"Exportar todos los logs"** is checked
3. Click **"Exportar a CSV"**
4. The CSV file will download automatically

## Technical Implementation

### Database Structure

Logs are stored in the `wp_reforestamos_chatbot_logs` table:

```sql
CREATE TABLE wp_reforestamos_chatbot_logs (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    session_id varchar(255) NOT NULL,
    user_message text NOT NULL,
    bot_response text NOT NULL,
    created_at datetime NOT NULL,
    PRIMARY KEY (id),
    KEY session_id (session_id),
    KEY created_at (created_at)
);
```

### Logging Process

Conversations are logged automatically when users interact with the chatbot:

1. User sends a message via the chatbot widget
2. Bot processes the message and generates a response
3. Both messages are logged to the database with:
   - Session ID (from cookie)
   - User message (sanitized)
   - Bot response
   - Timestamp

### Session Management

- Session IDs are generated using `wp_generate_password(32, false)`
- Stored in a cookie for 24 hours
- Allows tracking of multi-message conversations
- Each browser/device gets a unique session

### Security

- **Access Control**: Only users with `manage_options` capability can view logs
- **Nonce Verification**: Export functionality protected by WordPress nonces
- **Data Sanitization**: All input is sanitized before database queries
- **Prepared Statements**: All queries use WordPress prepared statements

### Performance Considerations

- **Pagination**: Limits results to 50 per page to prevent memory issues
- **Indexed Columns**: Database indexes on `session_id` and `created_at` for fast queries
- **Efficient Queries**: Uses optimized SQL with proper WHERE clauses
- **Session Limit**: Session dropdown limited to 100 most recent sessions

## Code Structure

### Files

- **includes/class-chatbot.php**: Main chatbot class with logging methods
  - `log_conversation()`: Logs each message exchange
  - `render_logs()`: Renders the logs admin page
  - `export_logs_csv()`: Handles CSV export

- **admin/views/chatbot-logs.php**: Admin interface view
  - Statistics cards
  - Filter form
  - Logs table
  - Pagination
  - Export section

### Hooks

```php
// Admin menu registration
add_submenu_page(
    'reforestamos-comunicacion',
    __( 'Logs de ChatBot', 'reforestamos-comunicacion' ),
    __( 'Logs de ChatBot', 'reforestamos-comunicacion' ),
    'manage_options',
    'reforestamos-chatbot-logs',
    array( 'Reforestamos_ChatBot', 'render_logs' )
);

// Export handler
add_action( 'admin_post_export_chatbot_logs', array( $this, 'export_logs_csv' ) );
```

## Use Cases

### 1. Analyzing User Questions

**Scenario**: Identify common questions users ask

**Steps**:
1. Access the logs page
2. Review user messages
3. Look for patterns in questions
4. Update chatbot responses to better address common queries

### 2. Improving Bot Responses

**Scenario**: Find conversations where the bot didn't provide helpful answers

**Steps**:
1. Search for generic responses like "no entendí"
2. Review the user messages that triggered these responses
3. Add new response patterns to handle these cases
4. Update conversation flows as needed

### 3. Monitoring Usage Trends

**Scenario**: Track chatbot usage over time

**Steps**:
1. Check "Mensajes Hoy" statistic daily
2. Use date filters to compare different time periods
3. Export data for trend analysis in spreadsheet
4. Identify peak usage times

### 4. Quality Assurance

**Scenario**: Verify chatbot is working correctly

**Steps**:
1. Review recent conversations
2. Check that responses are appropriate
3. Verify conversation flows are working
4. Identify any errors or issues

### 5. Reporting and Analytics

**Scenario**: Generate reports for stakeholders

**Steps**:
1. Export logs to CSV
2. Import into analytics tool (Excel, Google Sheets, etc.)
3. Create charts and visualizations
4. Generate insights and recommendations

## Best Practices

### For Administrators

1. **Regular Review**: Check logs weekly to identify issues
2. **Privacy**: Be mindful that logs contain user messages
3. **Data Retention**: Consider implementing a log cleanup policy
4. **Response Improvement**: Use insights to continuously improve responses

### For Developers

1. **Database Maintenance**: Monitor table size and performance
2. **Index Optimization**: Ensure indexes are working efficiently
3. **Error Handling**: Log any errors in the chatbot system
4. **Testing**: Test with large datasets to ensure performance

## Future Enhancements

Potential improvements for future versions:

1. **Conversation Analytics**
   - Sentiment analysis
   - Topic clustering
   - Response effectiveness metrics

2. **Visualization**
   - Charts showing usage trends
   - Conversation flow diagrams
   - Heatmaps of popular topics

3. **Advanced Filtering**
   - Filter by response type
   - Filter by conversation length
   - Filter by time of day

4. **Automated Insights**
   - AI-powered suggestions for response improvements
   - Automatic detection of problematic conversations
   - Weekly summary reports

5. **Data Management**
   - Automatic archiving of old logs
   - Configurable retention policies
   - Bulk delete functionality

## Troubleshooting

### Issue: No logs appearing

**Possible Causes**:
- Chatbot is disabled
- No conversations have occurred
- Database table not created

**Solution**:
1. Check chatbot is enabled in settings
2. Test chatbot on frontend
3. Verify database table exists

### Issue: Export fails

**Possible Causes**:
- Insufficient permissions
- Large dataset causing timeout
- Server memory limit

**Solution**:
1. Verify user has `manage_options` capability
2. Increase PHP memory limit if needed
3. Consider exporting in smaller batches

### Issue: Slow page load

**Possible Causes**:
- Large number of logs
- Missing database indexes
- Inefficient queries

**Solution**:
1. Implement log archiving
2. Verify database indexes exist
3. Optimize queries if needed

## Requirements Validation

This implementation satisfies **Requirement 10.6**:

✓ **THE Communication_Plugin SHALL log all chatbot conversations for analysis**
- All conversations are logged to database
- Logs include session ID, messages, responses, and timestamps
- Admin interface provides comprehensive viewing and analysis tools
- Export functionality enables external analysis
- Statistics provide quick insights
- Filtering and search enable detailed investigation

## Related Documentation

- [ChatBot Configuration Interface](./CHATBOT-CONFIG-INTERFACE.md)
- [ChatBot Conversation Flows](./CHATBOT-CONVERSATION-FLOWS.md)
- [Manual ChatBot Logs Test](../tests/manual-chatbot-logs-test.md)

## Support

For issues or questions about the conversation logs system:
1. Check this documentation
2. Review the manual test document
3. Check WordPress error logs
4. Contact the development team
