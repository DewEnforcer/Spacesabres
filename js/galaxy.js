const fetchSystemData = async () => {
  const response = await fetch("./include/getInfo.php", {
    method: "POST",
    headers: {
      "Content-type": "application/x-www-form-urlencoded",
    },
    body: `action=getSystemData&data=${currentSystem}`,
  });
  const data = await response.json();
  console.log(data);
  generatePlanets(data);
};
const generatePlanets = (data) => {
  Object.keys(data).forEach((item, i) => {});
};
$(document).ready(() => {
  fetchSystemData();
});
