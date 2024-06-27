const ctx = document.getElementById('myChart');
const mainData = null
fetch("/wp-json/lotes/v1/report")
    .then((response) => {
        if (!response.ok) { // Before parsing (i.e. decoding) the JSON data,
            // check for any errors.
            // In case of an error, throw.
            throw new Error("Something went wrong!");
        }

        return response.json(); // Parse the JSON data.
    })
    .then((data) => {
        // This is where you handle what to do with the response.
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(value => value.name),
                datasets: [
                    {
                        label: 'Lotes Disponibles',
                        data: data.map(value =>
                            value.available_count,
                        ),
                        borderWidth: 1,
                        backgroundColor: '#22CFCF',
                    },
                    {
                        label: 'Lotes Reservados',
                        data: data.map(value =>
                            value.reserved_count,
                        ),
                        borderWidth: 1,
                        backgroundColor: '#FFC234',
                    },
                    {
                        label: 'Lotes Vendidos',
                        data: data.map(value =>
                            value.sold_count,
                        ),
                        borderWidth: 1,
                        backgroundColor: '#FF6384',
                    }
                ],

            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


    })
    .catch((error) => {
        // This is where you handle errors.
        console.log(error)
    });

