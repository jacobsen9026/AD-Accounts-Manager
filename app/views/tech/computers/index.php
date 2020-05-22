<form action="/?goto=/tech/computers/rebootPC.php" method="post">
    <table id="container">
        <tr>
            <th>
                Reboot Workstation
            </th>
        </tr>
        <tr>
            <td>
                <br/>
                PC Name:
                <input name="PCName"/><br/>
                <br/>
                Time Delay:
                <input name="delay" value="0"/><br/>

                <br/><br/>
                <button type="submit" onclick="showMessege('Sending reboot command to workstation please wait...')">
                    Submit
                </button>
                <br/>
                <br/>
                <br/><br/>
            </td>
        </tr>
    </table>
</form>


<form action="/?goto=/tech/computers/renamePC.php" method="post">
    <table id="container">
        <tr>
            <th>
                Rename Workstation
            </th>
        </tr>
        <tr>
            <td>
                <br/>
                Current PC Name:
                <input name="currentPCName"/><br/>
                <br/>
                New PC Name:
                <input name="newPCName"/><br/>

                <br/><br/>
                <button type="submit" onclick="showMessege('Renaming workstation please wait...')">Submit</button>
                <br/>
                <br/>
                <!--
<a href="/?goto=/tech/computers/fixPowershell.php">
<button type="button">Fix Powershell Execution Policy</button>
</a>
-->
                <br/><br/>
            </td>
        </tr>
    </table>
</form>


<form action="/?goto=/tech/computers/termsrvHack.php" method="post">
    <table id="container">
        <tr>
            <th>
                Install RDP Multi-User Patch
            </th>
        </tr>
        <tr>
            <td>
                <br/>
                PC Name:
                <input name="PCName"/><br/>

                <br/><br/>
                Operating System<br/>
                <select name="os">
                    <option value="win10-1809-2">Windows 10 1809 w/Update</option>
                    <option value="old">Windows 7</option>
                    <option value="new">Windows 8</option>
                    <option value="win10">Windows 10</option>
                    <option value="win10-1703">Windows 10 1703</option>
                    <option value="win10-1709">Windows 10 1709</option>
                    <option value="win10-1803">Windows 10 1803</option>
                    <option value="win10-1809">Windows 10 1809</option>
                    <option value="win10-1903">Windows 10 1903</option>
                    <option value="win10-1909">Windows 10 1909</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <br/><br/>
                <button type="submit" onclick="showMessege('Applying RDP Multi-User hack please wait...')">Submit
                </button>
                <br/>
                <br/>


                <!--
    <a href="/?goto=/tech/computers/fixPowershell.php">
    <button type="button">Fix Powershell Execution Policy</button>
    </a>
    -->
                <br/><br/>
            </td>
        </tr>
    </table>
</form>


<form action="/?goto=/tech/computers/termsrvHack.php" method="post">
    <table id="container">
        <tr>
            <th>
                Flush the DNS?
            </th>
        </tr>

        <tr>
            <td>
                <input name="flushdns" value="true" hidden/>
                <button type="submit" onclick="showMessege('Flushing the DNS cache on the server please wait...')">Flush
                    It!
                </button>
            </td>
        </tr>
    </table>
</form>