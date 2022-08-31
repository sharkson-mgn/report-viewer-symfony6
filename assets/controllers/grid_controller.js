import { Grid } from "gridjs";
const $ = require('jquery');
import axios from 'axios';

var gridController = function() {

    var obj = {};

    obj.grid = new Grid({
        columns: ['Nazwa', 'Data', 'Godzina', 'Użytkownik', 'Lokal'],
        data: [],
        width: '100%',
        autoWidth: true,
        style: {
            table: {
                width: '100%',
            },
        },
    }).render(document.getElementById("table"));

    $("#filter").submit(function(e){

        let localName = $('#local').val() || '__ALL__';
        let from = Math.round(((new Date($('#from').val())).getTime() || 0) / 1000);
        let to = Math.round(((new Date($('#to').val())).getTime() || new Date().getTime()) / 1000);

        axios.get('/api/place/' + localName + '/' + from + '/' + to)
            .then(function(res) {
                let arr = [];
                for(let i in res.data) {
                    let item = res.data[i];
                    let date = new Date(item.exportAt.date);
                    arr.push([
                        item.name,
                        date.toISOString().split('T')[0],
                        date.toTimeString().split(' ')[0],
                        item.exportUser,
                        item.localName
                    ]);
                    obj.grid.updateConfig({
                        data: arr
                    }).forceRender();
                }
            });

        return false;
    });

    axios.get('/api/locals')
        .then(function(res) {
            for(let i in res.data) {
                $('#local').append('<option val="'+res.data[i]+'">'+res.data[i]+'</option>')
            }
        });

    return this;
}

$(document).ready(function() {
    gridController();
});

