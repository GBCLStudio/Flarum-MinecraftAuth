import app from 'flarum/admin/app';
import { ConfigureWithOAuthPage } from '@fof-oauth';

app.initializers.add('gbcl/minecraft-oauth', () => {
  app.extensionData.for('gbcl-minecraft-oauth').registerPage(ConfigureWithOAuthPage);
});