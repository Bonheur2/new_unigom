function exportTableToExcel(tableId, filename) {
  const table = document.getElementById(tableId);
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.table_to_sheet(table);
  
  XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
  
  const excelFile = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  const blob = new Blob([excelFile], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
  const link = document.createElement('a');
  
  const currentDate = new Date();
  const today = currentDate.toISOString().split('T')[0];

  link.href = URL.createObjectURL(blob);
  link.download = filename+ '_' + today + '.xlsx';
  link.style.display = 'none';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
