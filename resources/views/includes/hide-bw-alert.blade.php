 <script>
     //  Hide any BladewindUI .bw-alert element after some time
     const myDiv = document.querySelector('.bw-alert');
     setTimeout(() => {
         if (myDiv !== null) {
             myDiv.style.display = 'none';
         }
     }, 10000);
 </script>
