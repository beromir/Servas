import SwaggerUI from 'swagger-ui'
import 'swagger-ui/dist/swagger-ui.css';

const spec = require('./swagger-config.json');

const ui = SwaggerUI({
    spec,
    dom_id: '#swagger',
});
