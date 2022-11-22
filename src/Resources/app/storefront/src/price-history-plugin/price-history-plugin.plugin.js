import HttpClient from 'src/service/http-client.service';
import Plugin from 'src/plugin-system/plugin.class';

export default class PriceHistoryPlugin extends Plugin {
    init() {
        console.log('History Plugin loaded');
        this._client = new HttpClient();

        this.button = this.el.children['ajax-button'];
        this.textdiv = this.el.children['ajax-display'];
        this.id = this.el.children['ajax-button'].value;

        this._registerEvents();
    }

    _registerEvents() {
        this.button.onclick = this._fetch.bind(this);
    }

    _fetch() {
        this._client.get('/price_history/price_change/' + this.id, this._setContent.bind(this), 'application/json', true);
    }

    _setContent(data) {
        console.log(data);
        let historyData = JSON.parse(data);
        let html = '<ul>';
        Object.entries(historyData).forEach(([key, value]) => {
            html += '<li>' + key + ': ' + value.old + ' to '  + value.new + '</li>'
        });
        html += '</ul>'
        this.textdiv.innerHTML = html;
    }
}