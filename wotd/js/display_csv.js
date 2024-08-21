// JavaScript code in js/display_csv.js

// Define a function to fetch and display the CSV data
function fetchCSVAndDisplay(url) {
  fetch(url)
    .then((response) => response.text())
    .then((csvText) => {
      const rows = csvText.split("\n");
      let tableHTML = "<table>";

      // Parse headers from the first row
      const headers = parseCSVRow(rows[0]);

      // Create table headers
      tableHTML += "<thead><tr>";
      headers.forEach((header) => {
        tableHTML += `<th>${header}</th>`;
      });
      tableHTML += "</tr></thead><tbody>";

      // Start from the second row (data)
      for (let i = 1; i < rows.length; i++) {
        const columns = parseCSVRow(rows[i]);
        tableHTML += "<tr>";
        columns.forEach((column, index) => {
          const cellClass = `col-${index}`; // Assign class based on column index
          tableHTML += `<td class="${cellClass}">${column}</td>`;
        });
        tableHTML += "</tr>";
      }

      tableHTML += "</tbody></table>";
      document.getElementById("csv-table").innerHTML = tableHTML;
    })
    .catch((error) => console.error("Error fetching CSV:", error));
}

// Function to parse a CSV row while handling quoted values with commas
function parseCSVRow(row) {
  const columns = [];
  let currentColumn = "";
  let insideQuotes = false;

  for (let i = 0; i < row.length; i++) {
    const char = row.charAt(i);

    if (char === '"') {
      insideQuotes = !insideQuotes; // Toggle inside/outside quotes
    } else if (char === "," && !insideQuotes) {
      columns.push(currentColumn);
      currentColumn = "";
    } else {
      currentColumn += char;
    }
  }

  columns.push(currentColumn); // Add the last column
  return columns.map((column) => column.trim()); // Trim spaces
}

// Replace 'path/to/your/file.csv' with the path to your CSV file
fetchCSVAndDisplay("books_lst_complete.csv");
