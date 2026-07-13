print("[THUMBNAIL] Render Head")
local t = game:GetService("ThumbnailGenerator")
local player = game.Players:CreateLocalPlayer(0)
game:GetService("ContentProvider"):SetBaseUrl("http://noname.xyz/")
player.CharacterAppearance = "http://www.noname.xyz/char/1?rcc=true"
game:GetService("ScriptContext").ScriptsDisabled = true
player:LoadCharacter(false)
local Character = player.Character or player.CharacterAdded:Wait()
local FOV = 33
local AngleOffsetX = 0
local AngleOffsetY = 0
local AngleOffsetZ = 0
local CameraAngle = player.Character.Head.CFrame * CFrame.new(AngleOffsetX, AngleOffsetY, AngleOffsetZ)
local CameraPosition = player.Character.Head.CFrame + Vector3.new(0, 0, 0) + (CFrame.Angles(0, -0.20, 0).lookVector.unit * 3)
local Camera = Instance.new("Camera", player.Character)
Camera.Name = "ThumbnailCamera"
Camera.CameraType = Enum.CameraType.Scriptable                   
Camera.CoordinateFrame = CFrame.new(CameraPosition.p, CameraAngle.p)
Camera.FieldOfView = FOV
workspace.CurrentCamera = Camera
return t:Click("PNG", 420, 420, true)