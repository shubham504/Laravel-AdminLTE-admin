<!DOCTYPE html>
<html>
<head>
    <title>Import Users</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Import Users</h1>

    <!-- Display success or error messages -->
    <div id="message"></div>

    <!-- Import form -->
    <form id="import-form" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" id="file" />
        <button type="submit">Import Users</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#import-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('import.users') }}",
                    type: 'POST',
                    data: formData,
                    processData: false, // Prevent jQuery from automatically transforming the data into a query string
                    contentType: false, // Prevent jQuery from setting content type header
                    success: function(response) {
                        $('#message').html('<p style="color: green;">' + response.message + '</p>');
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '<p style="color: red;">' + Object.values(errors).join('<br>') + '</p>';
                        $('#message').html(errorMessage);
                    }
                });
            });
        });
    </script>
</body>
</html>