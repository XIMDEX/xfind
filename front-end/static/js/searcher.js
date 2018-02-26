function responseSolr(response) {
    var data = response.data;
    var facet_fields = data.facet_counts.facet_fields;
    var facet_ranges = data.facet_counts.facet_ranges;

    var hl = data.highlighting;

    this.data.provinces.facets = R.fromPairs(R.splitEvery(2, facet_fields.provinciadenunciado_s));
    this.data.recurred.facets = R.fromPairs(R.splitEvery(2, facet_fields.recurrida_s));
    this.data.date.facets = R.splitEvery(2, facet_ranges.fechafirma_rdt.counts);

    this.docs = data.response.docs;
    this.numFound = data.response.numFound;
    this.timeSearch = data.responseHeader.QTime;

    var datePairs = this.data.date.facets.map(function (data) {
        var date = new Date(data[0]);
        return R.pair(date.getFullYear(), data[1]);
    });

    this.data.date.facets = R.fromPairs(datePairs);

    this.docs.map(addHL(hl));

    //console.log(this.docs);
}

function addHL(hl) {
    return function (doc) {
        if (R.has('attr_contentfile', hl[doc.id])) {
            return Object.assign(doc,
                { hl: hl[doc.id].attr_contentfile[0] }
            );
        }
        return doc;
    }
}

function errorHandler(error) {
    console.log(error);
}

var objToQueryStr = R.pipe(
    R.toPairs,
    R.map(function (data) {
        if (R.is(Array, data[1])) {
            return data[1].map(function (q) {
                return data[0] + '=' + q;
            }).join('&');
        }
        return R.join("=", data);
    }),
    R.join("&")
);

var rows = 15;

var app = new Vue({
    el: '#searcher',
    data: {
        lastSearch: '',
        currentPage: 1,
        perRow: rows,
        data: {
            provinces: {
                type: 'province',
                text: "Provincias",
                facets: [],
                models: []
            },
            recurred: {
                type: 'recurred',
                text: "Recurridas",
                facets: [],
                models: []
            },
            date: {
                type: 'date',
                text: "Fechas",
                facets: [],
                models: []
            }
        },
        provinciasModel: [],
        recurridasModel: [],
        dateModel: [],
        docs: [],
        numFound: 0,
        searchText: '',
        timeSearch: 0,
        uri: "http://lab04:8983/solr/mycore/select",
        resource_url: '',
        qs: {
            'facet.field': ['provinciadenunciado_s', 'recurrida_s'],
            'facet': 'on',
            'rows': rows,
            'start': 0,
            'facet.range': 'fechafirma_rdt',
            'f.fechafirma_rdt.facet.range.start': '2010-01-1T17:33:18.772Z',
            'f.fechafirma_rdt.facet.range.end': 'NOW',
            'f.fechafirma_rdt.facet.range.gap': encodeURIComponent('+1YEAR'),
            'q': '*:*',
            'hl': 'on',
            'hl.fl': 'attr_contentfile',
            'fq': ['', '', '']
        }
    },
    methods: {
        clickCallback: function (pageNum) {
            var page = (pageNum - 1) * this.perRow;
            this.qs = R.merge(this.qs, {
                start: page
            });

            var uri = this.uri + '?' + objToQueryStr(this.qs);

            axios.get(uri)
                .then(responseSolr.bind(this))
                .catch(errorHandler);
        },
        updateResource: function (data) {
            responseSolr(data).bind(this);
        },
        computedClass: function (value, key) {
            return this[key][this[key].length - 1] === value ? 'active-facet' : '';
        },
        selectFacets: function (type) {
            if (type === 'province') {
                this.selectProvincias()
            } else if (type === 'recurred') {
                this.selectRecurridas();
            } else if (type === 'date') {
                this.selectDate();
            }
        },
        selectDate: function () {
            var model = this.data.date.models;

            var date = model[model.length - 1];
            this.data.date.models = [date];

            this.qs = R.merge(this.qs, {
                start: 0,
                fq: date ? R.update(2, 'fechafirma_rdt:' + date, this.qs.fq) : R.update(2, '', this.qs.fq)
            });

            var uri = this.uri + '?' + objToQueryStr(this.qs);

            axios.get(uri)
                .then(responseSolr.bind(this))
                .catch(errorHandler);
        },
        selectProvincias: function () {
            var model = this.data.provinces.models;

            var city = model[model.length - 1];
            this.data.provinces.models = [city];
            this.qs = R.merge(this.qs, {
                start: 0,
                fq: city ? R.update(0, 'provinciadenunciado_s:' + city, this.qs.fq) : R.update(0, '', this.qs.fq)
            });

            var uri = this.uri + '?' + objToQueryStr(this.qs);

            axios.get(uri)
                .then(responseSolr.bind(this))
                .catch(errorHandler);
        },
        selectRecurridas: function () {
            var model = this.data.recurred.models;

            var data = model[model.length - 1];
            this.data.recurred.models = data;

            this.qs = R.merge(this.qs, {
                start: 0,
                fq: data ? R.update(1, 'recurrida_s:' + data, this.qs.fq) : R.update(1, '', this.qs.fq)
            });

            var uri = this.uri + '?' + objToQueryStr(this.qs);

            axios.get(uri)
                .then(responseSolr.bind(this))
                .catch(errorHandler);
        },
        search: function () {
            this.lastSearch = this.searchText;

            this.qs = R.merge(this.qs, {
                start: 0,
                q: this.searchText ? 'attr_contentfile:' + this.searchText : '*:*'
            });

            var uri = this.uri + '?' + objToQueryStr(this.qs);

            axios.get(uri)
                .then(responseSolr.bind(this))
                .catch(errorHandler);
        }
    },
    created: function () {
        var uri = this.uri + '?' + objToQueryStr(this.qs);
        axios.get(uri)
            .then(responseSolr.bind(this))
            .catch(errorHandler);
    }
});

delete rows;