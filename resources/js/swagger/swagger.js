import SwaggerUI from 'swagger-ui';
import 'swagger-ui/dist/swagger-ui.css';

let spec = import('./swagger-config.json');

SwaggerUI({
    spec: spec,
    dom_id: '#swagger',
});
