<script setup>
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';

DataTable.use(DataTablesCore);

const columns = [
    { data: 'email' },
    { data: 'name' },
    { data: 'country' },
    { data: 'subscribe_date' },
    { data: 'subscribe_time' },
];
</script>

<template>
    <div class="container">
        <h1>Manage Mailerlite subscribers</h1>
        <p>
            Showing the subscribers of an account:

            Use DataTables (https://datatables.net/) to display all the subscribers of the account. The email, name, country, subscribe date and subscribe time of each subscriber should be displayed on the table (5 fields).
            An account may have millions of subscribers so it is not efficient to load and show all of them at once. For that reason, you should implement pagination and only load a few subscribers for each page. To implement this feature, please use the server side data source of the DataTables library.
            It should be possible to use the search field to search for subscribers by email and to change the number of subscribers displayed per page.
            Do not do any direct calls to the MailerLite API from the frontend. Instead, proxy all requests through the backend of your application.
            The subscribe date should be in the day/month/year format while the subscribe time should be in the 24-hour format with leading zeroes.
        </p>

        <DataTable
            :columns="columns"
            ajax="/data.json"
            class="table table-hover table-striped"
            width="100%"
        >
            <thead>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>country</th>
                <th>Subscribe date</th>
                <th>Subscribe time</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>country</th>
                <th>Subscribe date</th>
                <th>Subscribe time</th>
            </tr>
            </tfoot>
        </DataTable>
    </div>
</template>

<style>
@import 'bootstrap';
@import 'datatables.net-bs5';
</style>
